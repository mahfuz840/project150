package com.the_spartan.run.activities;

import android.app.ProgressDialog;
import android.content.Intent;
import android.provider.ContactsContract;
import android.support.annotation.NonNull;
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
import com.the_spartan.run.helper.SQLiteHandler;
import com.the_spartan.run.volley.AppController;
import com.the_spartan.run.volley.Config_URL;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class ProfileActivity extends AppCompatActivity {

    private DrawerLayout mDrawerLayout;
    private NavigationView navigationView;
    private ActionBarDrawerToggle mToggle;

    ProgressDialog pDialog;
    private static final String TAG = "ProfileActivity";
    private int backPressed;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profile);

        backPressed = 0;

        getSupportActionBar().setTitle(R.string.profile_activity_name);

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
                                Intent homeIntent = new Intent(ProfileActivity.this, HomeActivity.class);
                                startActivity(homeIntent);
                                break;
                            case R.id.dashboard:
                                Intent dashboardIntent = new Intent(ProfileActivity.this, RankingActivity.class);
                                startActivity(dashboardIntent);
                                break;
                            case R.id.my_profile:
                                Intent profileIntent = new Intent(ProfileActivity.this, ProfileActivity.class);
                                startActivity(profileIntent);
                                break;
                            case R.id.tips:
                                Intent foodIntent = new Intent(ProfileActivity.this, TipsActivity.class);
                                startActivity(foodIntent);
                                break;

//                            case R.id.speedo:
//                                Intent speedoIntent = new Intent(ProfileActivity.this, Speedo.class);
//                                startActivity(speedoIntent);
//                                break;
                        }
                        item.setChecked(true);
                        mDrawerLayout.closeDrawers();
                        return true;
                    }
                });

        showProfile();

    }


    private void showProfile(){
        String tag_string_req = "req_profile";

        pDialog = new ProgressDialog(ProfileActivity.this);
        pDialog.setMessage("Loading Your Profile...");
        showDialog();

        SQLiteHandler db = new SQLiteHandler(ProfileActivity.this);
        HashMap<String, String> user = db.getUserDetails();

        String uid = user.get("uid");

        StringRequest stringRequest = new StringRequest(Request.Method.POST, Config_URL.URL_REGISTER, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Log.d(TAG, "Profile Response: " + response.toString());
                hideDialog();

                try{
                    JSONObject obj = new JSONObject(response);
                    boolean error = obj.getBoolean("error");

                    if (!error){
                        String name = obj.getString("name");
                        String email = obj.getString("email");
                        String age = obj.getString("age");
                        String height = obj.getString("height");
                        String weight = obj.getString("weight");
                        String waist = obj.getString("waist");
                        String neck = obj.getString("neck");

                        setDetails(name, email, age, height, weight, waist, neck);
                    } else {
                        Toast.makeText(ProfileActivity.this, "error occurred!", Toast.LENGTH_SHORT).show();
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
                params.put("tag", "profile");
                params.put("uid", uid);
                return params;
            }
        };

        AppController.getInstance().addToRequestQueue(stringRequest, tag_string_req);

    }

    private void showDialog(){
        if (!pDialog.isShowing())
            pDialog.show();
    }

    private void hideDialog(){
        if (pDialog.isShowing())
            pDialog.dismiss();
    }

    private void setDetails(String name, String email, String age, String height, String weight, String waist, String neck){
        TextView nameTextView = findViewById(R.id.user_profile_name);
        TextView emailTextView = findViewById(R.id.user_email);
        TextView ageTextView = findViewById(R.id.user_age);
        TextView heightTextView = findViewById(R.id.user_height);
        TextView weighTextView = findViewById(R.id.user_weight);
        TextView waistTextView = findViewById(R.id.user_waist);
        TextView neckTextView = findViewById(R.id.user_neck);

        nameTextView.setText(name);
        emailTextView.setText("Email: " + email);
        ageTextView.setText("Age: " + age);
        heightTextView.setText("Height: " + height);
        weighTextView.setText("Weight: " + weight);
        waistTextView.setText("Waist: " + waist);
        neckTextView.setText("Neck: " + neck);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {

        if (mToggle.onOptionsItemSelected(item)){
            return true;
        }

        return true;

    }

    @Override
    public void onBackPressed() {
        backPressed++;
        if (backPressed >= 2){
            super.onBackPressed();
        } else {
            Toast.makeText(ProfileActivity.this, "Press Back Again to Exit", Toast.LENGTH_SHORT).show();
        }
    }
}
