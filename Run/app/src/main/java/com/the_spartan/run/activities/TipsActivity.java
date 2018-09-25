package com.the_spartan.run.activities;

import android.app.ProgressDialog;
import android.content.Intent;
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
import com.the_spartan.run.volley.AppController;
import com.the_spartan.run.volley.Config_URL;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class TipsActivity extends AppCompatActivity {

    private DrawerLayout mDrawerLayout;
    private NavigationView navigationView;
    private ActionBarDrawerToggle mToggle;
    private TextView foodTipsTextView;

    private static final String TAG = "FoodTipsActivity";

    ProgressDialog pDialog;

    private int backPressed;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_tips);
        getSupportActionBar().setTitle(R.string.tips_activity_name);

        backPressed = 0;

        mDrawerLayout = findViewById(R.id.drawer_layout);
        mToggle = new ActionBarDrawerToggle(this, mDrawerLayout, R.string.open, R.string.close);
        mDrawerLayout.addDrawerListener(mToggle);
        mToggle.syncState();
//        android.support.v7.widget.Toolbar toolbar = findViewById(R.id.toolbar);
//        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        foodTipsTextView = findViewById(R.id.tips_textView);


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
                                Intent homeIntent = new Intent(TipsActivity.this, HomeActivity.class);
                                startActivity(homeIntent);
                                break;
                            case R.id.dashboard:
                                Intent dashboardIntent = new Intent(TipsActivity.this, RankingActivity.class);
                                startActivity(dashboardIntent);
                                break;
                            case R.id.my_profile:
                                Intent profileIntent = new Intent(TipsActivity.this, ProfileActivity.class);
                                startActivity(profileIntent);
                                break;
                            case R.id.tips:
                                Intent foodIntent = new Intent(TipsActivity.this, TipsActivity.class);
                                startActivity(foodIntent);
                                break;
//                            case R.id.speedo:
//                                Intent speedoIntent = new Intent(FoodTipsActivity.this, Speedo.class);
//                                startActivity(speedoIntent);
//                                break;
                        }
                        item.setChecked(true);
                        mDrawerLayout.closeDrawers();
                        return true;
                    }
                });

        showFoodTips();

    }


    private void showFoodTips(){
        String tag_string_req = "req_show_foodtips";

        pDialog = new ProgressDialog(TipsActivity.this);
        pDialog.setMessage("Loading data ...");
        showDialog();

        StringRequest strReq = new StringRequest(Request.Method.POST,
                Config_URL.URL_REGISTER,
                new Response.Listener<String>() {

                    @Override
                    public void onResponse(String response) {
                        Log.d(TAG, "FoodTips Response: " + response.toString());
                        hideDialog();

                        try {
                            JSONObject jsonObject = new JSONObject(response);
                            boolean error = jsonObject.getBoolean("error");
                            if (!error){
                                String tips = jsonObject.getString("tips");
                                foodTipsTextView.setText(tips);
                            } else {
                                Toast.makeText(TipsActivity.this, "error occurred", Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }

                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e(TAG, "FoodTips Error: " + error.getMessage());
                Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
                hideDialog();
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                // Posting params to register url
                Map<String, String> params = new HashMap<String, String>();
                params.put("tag", "foodtips");

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
            Toast.makeText(TipsActivity.this, "Press Back Again to Exit", Toast.LENGTH_SHORT).show();
        }
    }
}
