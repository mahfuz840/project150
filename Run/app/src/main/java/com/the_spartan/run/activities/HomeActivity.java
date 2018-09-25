package com.the_spartan.run.activities;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.support.annotation.NonNull;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.NavigationView;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.the_spartan.myapplication.R;
import com.the_spartan.run.MainActivity;
import com.the_spartan.run.helper.SQLiteHandler;
import com.the_spartan.run.helper.SessionManager;
import com.the_spartan.run.volley.AppController;
import com.the_spartan.run.volley.Config_URL;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class HomeActivity extends AppCompatActivity {

    public static final String TAG = "HomeActivity";

    private DrawerLayout mDrawerLayout;
    private NavigationView navigationView;
    private ActionBarDrawerToggle mToggle;
    private FloatingActionButton startActivity;
    private ProgressDialog pDialog;
    private SQLiteHandler db;

    private  int backPressed;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);
        getSupportActionBar().setTitle(R.string.home_activity_name);

        backPressed = 0;

        db = new SQLiteHandler(HomeActivity.this);

        mDrawerLayout = findViewById(R.id.drawer_layout);
        mToggle = new ActionBarDrawerToggle(this, mDrawerLayout, R.string.open, R.string.close);
        mDrawerLayout.addDrawerListener(mToggle);
        mToggle.syncState();
//        android.support.v7.widget.Toolbar toolbar = findViewById(R.id.toolbar);
//        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);


        mDrawerLayout = findViewById(R.id.drawer_layout);
        mDrawerLayout.addDrawerListener(
                new DrawerLayout.DrawerListener() {
                    @Override
                    public void onDrawerSlide(@NonNull View drawerView, float slideOffset) {

                    }

                    @Override
                    public void onDrawerOpened(@NonNull View drawerView) {

                    }

                    @Override
                    public void onDrawerClosed(@NonNull View drawerView) {

                    }

                    @Override
                    public void onDrawerStateChanged(int newState) {

                    }
                }
        );
        NavigationView navigationView = findViewById(R.id.nav_view);
        navigationView.setNavigationItemSelectedListener(
            new NavigationView.OnNavigationItemSelectedListener() {
                @Override
                public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                    switch (item.getItemId()){
                        case R.id.home:
                            break;
                        case R.id.dashboard:
                            Intent dashboardIntent = new Intent(HomeActivity.this, RankingActivity.class);
                            startActivity(dashboardIntent);
                            break;
                        case R.id.my_profile:
                            Intent profileIntent = new Intent(HomeActivity.this, ProfileActivity.class);
                            startActivity(profileIntent);
                            break;
                        case R.id.tips:
                            Intent tipsIntent = new Intent(HomeActivity.this, TipsActivity.class);
                            startActivity(tipsIntent);
                            break;
                        case R.id.logout:
                            SessionManager session = new SessionManager(HomeActivity.this);
                            session.setLogin(false);
                            Intent mainIntent = new Intent(HomeActivity.this, MainActivity.class);
                            startActivity(mainIntent);
                            break;
                    }
                    item.setChecked(true);
                    mDrawerLayout.closeDrawers();
                    return true;
                }
            });

        startActivity = findViewById(R.id.fab);
        startActivity.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                checkFormCompleteness();
            }
        });

        showDashboard();

    }

    private void showDashboard(){
        String tag_string_req = "req_dashboard";

        pDialog = new ProgressDialog(HomeActivity.this);
        pDialog.setMessage("Loading Your Activities...");
//        showDialog();

        SQLiteHandler db = new SQLiteHandler(HomeActivity.this);
        HashMap<String, String> user = db.getUserDetails();

        String uid = user.get("uid");
        Log.d(TAG, uid);

        StringRequest stringRequest = new StringRequest(Request.Method.POST,
                Config_URL.URL_REGISTER,
                new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Log.d(TAG, "Dashboard Response: " + response.toString());
                hideDialog();

                try{
                    JSONObject obj = new JSONObject(response);
                    boolean error = obj.getBoolean("error");

                    if (!error){
//                        Toast.makeText(HomeActivity.this, "Success!", Toast.LENGTH_SHORT).show();

                        String bmi = obj.getString("bmi");
                        String bodyFatPercentage = obj.getString("body_fat_percentage");
                        String bodyFatMass = obj.getString("body_fat_mass");
                        String totalDistance = obj.getString("total_distance");
                        String caloriesToBurn = obj.getString("calories_to_burn");
                        String remainingCalorie = obj.getString("remaining_calorie");

                        setDash(bmi, bodyFatPercentage, bodyFatMass, totalDistance, caloriesToBurn, remainingCalorie);
                    } else {
                        Toast.makeText(HomeActivity.this, "error occurred!", Toast.LENGTH_SHORT).show();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

            }
        }) {
            @Override
            protected Map<String, String> getParams(){

                HashMap<String, String> params = new HashMap<>();
                params.put("tag", "dashboard");
                params.put("uid", uid);
                return params;
            }
        };

        AppController.getInstance().addToRequestQueue(stringRequest, tag_string_req);
    }

    private void setDash(String bmi, String bodyFatPercentage, String bodyFatMass,
                         String totalDistance, String caloriesToBurn, String remainingCalorie){
        TextView bmiTextView = findViewById(R.id.bmi_textview);
        TextView bodyFatPercentageTextView = findViewById(R.id.fat_percentage_textview);
        TextView bodyFatMassTextView = findViewById(R.id.fat_mass_textview);
        TextView totalDistanceTextView = findViewById(R.id.distance_textview);
        TextView caloriesToBurnTextView = findViewById(R.id.calories_to_burn_textview);
        TextView remainingCalorieTextView = findViewById(R.id.remaining_calorie_textview);

        if (bmi.isEmpty() || bmi == null){
            bmiTextView.setText("unavailable");
            bodyFatPercentageTextView.setText("unavailable");
            bodyFatMassTextView.setText("unavailable");
            totalDistanceTextView.setText("unavailable");
            caloriesToBurnTextView.setText("unavailable");
            remainingCalorieTextView.setText("unavailable");
        } else {
            bmiTextView.setText(bmi);
            bodyFatPercentageTextView.setText(bodyFatPercentage + "%");
            bodyFatMassTextView.setText(bodyFatMass + "kg");
            totalDistanceTextView.setText(totalDistance + "m");
            caloriesToBurnTextView.setText(caloriesToBurn + "Cal");
            remainingCalorieTextView.setText(remainingCalorie + "Cal");
        }

    }


    @Override
    public boolean onOptionsItemSelected(MenuItem item) {

        if (mToggle.onOptionsItemSelected(item)){
            return true;
        }

        return true;

    }

    private void checkFormCompleteness(){

        pDialog = new ProgressDialog(HomeActivity.this);
        pDialog.setCancelable(false);

        String tag_string_req = "req_update";

        pDialog.setMessage("Checking your account ...");
        showDialog();

        HashMap<String, String> user = db.getUserDetails();
        String email = user.get("email");

        Log.d(TAG, " " + email);

        StringRequest strReq = new StringRequest(Request.Method.POST,
                Config_URL.URL_UPDATE,
                new Response.Listener<String>() {

                    @Override
                    public void onResponse(String response) {
                        Log.d(TAG, "Update Response: " + response.toString());
                        hideDialog();

                        try {
                            JSONObject jObj = new JSONObject(response);
                            boolean error = jObj.getBoolean("error");

                            // Check for error node in json
                            if (!error) {
                                //user age weight height are filed up or not
                                boolean isComplete = jObj.getBoolean("completeness");

                                if (isComplete){
                                    Intent speedoIntent = new Intent(HomeActivity.this, Speedometer.class);
                                    startActivity(speedoIntent);
                                } else {
                                    AlertDialog.Builder builder = new AlertDialog.Builder(HomeActivity.this, R.style.MyAlertDialogTheme);
                                    builder.setTitle("Halt!")
                                            .setMessage("Please fill up the informations to continue...")
                                            .setPositiveButton("Take me to the form", new DialogInterface.OnClickListener() {
                                                @Override
                                                public void onClick(DialogInterface dialogInterface, int i) {
                                                    Intent completeFormIntent = new Intent(HomeActivity.this, CompleteFormActivity.class);
                                                    startActivity(completeFormIntent);
                                                }
                                            }).show();

                                }

                            } else {
                                // Error in login. Get the error message
                                String errorMsg = jObj.getString("error_msg");
                                Toast.makeText(HomeActivity.this,"JSON ERROR" + errorMsg, Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            // JSON error
                            e.printStackTrace();
                        }

                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e(TAG, "Login Error: " + error.getMessage());
                Toast.makeText(HomeActivity.this,"Login error" + error.getMessage(), Toast.LENGTH_LONG).show();
                hideDialog();
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                // Posting parameters to update url
                Map<String, String> params = new HashMap<String, String>();
                params.put("tag", "update");
                params.put("email", email);

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
        backPressed++;
        if (backPressed >= 2){
            super.onBackPressed();
        } else {
            Toast.makeText(HomeActivity.this, "Press Back Again to Exit", Toast.LENGTH_SHORT).show();
        }
    }

}
