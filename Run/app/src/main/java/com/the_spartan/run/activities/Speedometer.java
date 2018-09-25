package com.the_spartan.run.activities;

import android.Manifest;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Paint;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.support.annotation.NonNull;
import android.support.v4.app.ActivityCompat;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.the_spartan.myapplication.R;
import com.the_spartan.run.SpeedView;
import com.the_spartan.run.Speedo;
import com.the_spartan.run.design.GaugeView;
import com.the_spartan.run.helper.SQLiteHandler;
import com.the_spartan.run.volley.AppController;
import com.the_spartan.run.volley.Config_URL;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.DecimalFormat;
import java.util.HashMap;
import java.util.Map;

public class Speedometer extends AppCompatActivity {

    private static final String TAG = "Speedometer";

    public LocationManager lm;
    public LocationListener locationListener;
    public Integer data_points = 2; // how many data points to calculate for
    public Double[][] positions;
    public Long[] times;
    public Boolean mirror_pref, full_screen_pref; // Preference Booleans
    public Integer units; // Preference integers
    public Float text_size; // Preference Float

    public int satellites = 0;
    public Paint gradPaint = null;
    public float maxSpeedInVisualization = 150f;
    private boolean isLocServiceRunning = false;

    public float maxSpeed = 0;
    public double distance = 0;
    DecimalFormat df = new DecimalFormat("##00.00");

    private TextView tvCurrentSpeed;
    private TextView tvDistance;
    private TextView tvMaxSpeed;
    private Button btnStart;
    private Button btnStop;
    private GaugeView gauge;

    //for gauge view
    private float degree = -225;
    private float sweepAngleControl = 0;
    private float sweepAngleFirstChart = 1;
    private float sweepAngleSecondChart = 1;
    private float sweepAngleThirdChart = 1;
    private boolean isInProgress = false;
    private boolean resetMode = false;
    private boolean canReset = false;
    private int prevSpeed = 0;
    private boolean increasing = false;
    private boolean decreasing = false;

    private ProgressDialog pDialog;
    private SQLiteHandler db;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_speedometer);

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        gauge = findViewById(R.id.current_speed_gauge);
        gauge.setRotateDegree(degree);
        tvCurrentSpeed = findViewById(R.id.tv_current_speed);
        tvDistance = findViewById(R.id.tv_distance);
//        tvMaxSpeed = findViewById(R.id.tv_max_speed);
        btnStart = findViewById(R.id.btn_start);
        btnStop = findViewById(R.id.btn_stop);

        // two arrays for position and time.
        positions = new Double[data_points][2];
        times = new Long[data_points];

        // setting and running location manager
        lm = (LocationManager) getSystemService(Context.LOCATION_SERVICE);

    }

    @Override
    public void onStop() {
        super.onStop();
    }

    @Override
    public void onPause() {
        if (locationListener != null){
            lm.removeUpdates(locationListener);
            locationListener = null;
        }
        super.onPause();
    }


    @Override
    public void onResume() {
        super.onResume();

        btnStart.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if (ActivityCompat.checkSelfPermission(Speedometer.this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED
                        &&
                        ActivityCompat.checkSelfPermission(Speedometer.this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
//                    ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, 200);

                    return;
                }

                tvCurrentSpeed.setText("Initializing Tracking Service...");

                isInProgress = true;

                if (!isLocServiceRunning) {
                    locationListener = new MyLocationListener();
                    lm.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, locationListener);
                    isLocServiceRunning = true;
                }
            }
        });



        btnStop.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                isInProgress = false;
                resetMode = true;
                resetGauges();
                if (isLocServiceRunning) {
                    lm.removeUpdates(locationListener);
                    locationListener = null;
                    isLocServiceRunning = false;
                }

                uploadData();
            }
        });

    }

    private class MyLocationListener implements LocationListener {
        Integer counter = 0;

        public void onLocationChanged(Location loc) {

//            Toast.makeText(Speedometer.this, "Location changed", Toast.LENGTH_SHORT).show();

            if (loc != null) {
                Double d1;
                Long t1;
                Double speed = 0.0;
                d1 = 0.0;
                t1 = 0l;

                positions[counter][0] = loc.getLatitude();
                positions[counter][1] = loc.getLongitude();
                times[counter] = loc.getTime();

                try {
                    // get the distance and time between the current position, and the previous position.
                    // using (counter - 1) % data_points doesn't wrap properly
                    d1 = distance(positions[counter][0], positions[counter][1], positions[(counter + (data_points - 1)) % data_points][0], positions[(counter + (data_points - 1)) % data_points][1]);
                    t1 = times[counter] - times[(counter + (data_points - 1)) % data_points];
                } catch (NullPointerException e) {
                    //all good, just not enough data yet.
                }

                if (loc.hasSpeed()) {
                    speed = loc.getSpeed() * 1.0; // need to * 1.0 to get into a double for some reason...
                } else {
                    speed = d1 / t1; // m/s
                }
                counter = (counter + 1) % data_points;

                // convert from m/s to kmh
//                switch (units) {
//                    case R.id.kmph:
                speed = speed * 3.6d;

//                speedView.setSpeed(speed.intValue());
//                tvCurrentSpeed.setText(String.valueOf(speed.intValue()));
                updateGauge(speed.floatValue());

                maxSpeed = Math.max(maxSpeed, speed.intValue());
//                tvMaxSpeed.setText("Max Speed : " + String.valueOf(maxSpeed) + "km/h");
                tvCurrentSpeed.setText("Current Speed : " + String.valueOf(speed) + "km/h");
                addDistance(d1);
                tvDistance.setText("Distance : " + String.valueOf((int)distance + "m"));
            } else {
//                speedView.setSpeed(-1);
                tvCurrentSpeed.setText("Current Speed : unknown");
            }
        }

        public void onProviderDisabled(String provider) {
            // TODO Auto-generated method stub
            Log.i(getResources().getString(R.string.app_name), "Speedo provider disabled : " + provider);
        }


        public void onProviderEnabled(String provider) {
            // TODO Auto-generated method stub
            Log.i(getResources().getString(R.string.app_name), "Speedo provider enabled : " + provider);
        }


        public void onStatusChanged(String provider, int status, Bundle extras) {
            // TODO Auto-generated method stub
            Log.i(getResources().getString(R.string.app_name), "Speedo status changed : " + extras.toString());
            if (extras.get("satellites") != null) {
                setSatellites(extras.getInt("satellites"));
            }
        }

        // private functions
        private double distance(double lat1, double lon1, double lat2, double lon2) {
            // haversine great circle distance approximation, returns meters
            double theta = lon1 - lon2;
            double dist = Math.sin(deg2rad(lat1)) * Math.sin(deg2rad(lat2)) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.cos(deg2rad(theta));
            dist = Math.acos(dist);
            dist = rad2deg(dist);
            dist = dist * 60; // 60 nautical miles per degree of seperation
            dist = dist * 1852; // 1852 meters per nautical mile
            return (dist);
        }

        private double deg2rad(double deg) {
            return (deg * Math.PI / 180.0);
        }

        private double rad2deg(double rad) {
            return (rad * 180.0 / Math.PI);
        }
    }

    public void setSatellites(int satellites) {
        this.satellites = satellites;
    }

    public void addDistance(double meters) {
        this.distance += meters;
    }


    //this is for reseting gauge
    private void resetGauges() {
        new Thread() {
            public void run() {
                for (int i = 0; i < (int)degree; i++) {
                    try {
                        runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                sweepAngleControl--;
                                sweepAngleFirstChart = 1;
                                sweepAngleSecondChart = 1;
                                sweepAngleThirdChart = 1;

                                degree--;
                                gauge.setSweepAngleFirstChart(0);
                                gauge.setSweepAngleSecondChart(0);
                                gauge.setSweepAngleThirdChart(0);
                                gauge.setRotateDegree(degree);
                            }
                        });
                        Thread.sleep(1);
                    } catch (InterruptedException e) {
                        e.printStackTrace();
                    }

                    if (i == 299) {
                        resetMode = false;
                        canReset = false;
                    }

                }
            }
        }.start();
    }

    private void updateGauge(float speed) {
        int difference = Math.abs(prevSpeed - (int)speed);

        if (prevSpeed != (int)speed){
            if (prevSpeed - (int)speed > 1){
                decreasing = true;
                increasing = false;
            } else {
                decreasing = false;
                increasing = true;
            }
            prevSpeed = (int)speed;
        }

        new Thread() {
            public void run() {
                for (int i = 0; i < difference * 10 ; i++) {
                    try {
                        runOnUiThread(new Runnable() {

                            @Override
                            public void run() {
                                if (increasing){
                                    degree++;
                                    sweepAngleControl++;
                                } else {
                                    degree--;
                                    sweepAngleControl--;
                                }

                                if (degree < 45) {
                                    gauge.setRotateDegree(degree);
                                }

                                if (sweepAngleControl <= 90) {
                                    sweepAngleFirstChart++;
                                    gauge.setSweepAngleFirstChart(sweepAngleFirstChart);
                                } else if (sweepAngleControl <= 180) {
                                    sweepAngleSecondChart++;
                                    gauge.setSweepAngleSecondChart(sweepAngleSecondChart);
                                } else if (sweepAngleControl <= 270) {
                                    sweepAngleThirdChart++;
                                    gauge.setSweepAngleThirdChart(sweepAngleThirdChart);
                                }

                            }
                        });
                        Thread.sleep(15);
                    } catch (InterruptedException e) {
                        e.printStackTrace();
                    }

                    if (i == 299) {
                        isInProgress = false;
                        canReset = true;
                    }

                }
            }
        }.start();
    }


    private void uploadData(){
        pDialog = new ProgressDialog(Speedometer.this);
        pDialog.setCancelable(false);

        String tag_string_req = "req_upload_distance";

        pDialog.setMessage("Uploading your activity ...");
        showDialog();

        db = new SQLiteHandler(Speedometer.this);

        HashMap<String, String> user = db.getUserDetails();
        String uid = user.get("uid");

        Log.d(TAG, " " + uid);

        StringRequest strReq = new StringRequest(Request.Method.POST,
                Config_URL.URL_UPDATE,
                new Response.Listener<String>() {

                    @Override
                    public void onResponse(String response) {
                        Log.d(TAG, "Upload Response: " + response.toString());
                        hideDialog();

                        try {
                            JSONObject jObj = new JSONObject(response);
                            boolean error = jObj.getBoolean("error");

                            // Check for error node in json
                            if (!error) {
                                //user data uploaded or not
                                Log.d(TAG, "UPLOADED SUCCESSFULLY");
                            } else {
                                // Error in login. Get the error message
                                String errorMsg = jObj.getString("error_msg");
                                Toast.makeText(Speedometer.this,"JSON ERROR" + errorMsg, Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            // JSON error
                            e.printStackTrace();
                        }

                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e(TAG, "UPLOAD Error: " + error.getMessage());
                Toast.makeText(Speedometer.this,"Login error" + error.getMessage(), Toast.LENGTH_LONG).show();
                hideDialog();
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                // Posting parameters to update url
                Map<String, String> params = new HashMap<String, String>();
                params.put("tag", "upload_distance");
                params.put("uid", uid);
                params.put("distance", String.valueOf(distance));

                return params;
            }

        };

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(strReq, tag_string_req);
    }

    private void showDialog() {
        if (!pDialog.isShowing()) pDialog.show();
    }

    private void hideDialog() {
        if (pDialog.isShowing()) pDialog.dismiss();
    }

    @Override
    public void onBackPressed() {
            Intent homeIntent = new Intent(Speedometer.this, HomeActivity.class);
            startActivity(homeIntent);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        if (item.getItemId() == android.R.id.home){
            Intent homeIntent = new Intent(Speedometer.this, HomeActivity.class);
            startActivity(homeIntent);
        }

        return true;
    }
}
