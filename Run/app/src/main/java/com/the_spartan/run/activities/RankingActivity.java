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
import android.widget.ListView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.the_spartan.myapplication.R;
import com.the_spartan.run.Person;
import com.the_spartan.run.PersonsAdapter;
import com.the_spartan.run.volley.AppController;
import com.the_spartan.run.volley.Config_URL;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class RankingActivity extends AppCompatActivity {

    private DrawerLayout mDrawerLayout;
    private NavigationView navigationView;
    private ActionBarDrawerToggle mToggle;
    private ListView listView;
    private ProgressDialog pDialog;

    private static String TAG = "RankingActivity";

    private int backPressed;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_ranking);
        getSupportActionBar().setTitle(R.string.ranking_activity_name);

        backPressed = 0;

        listView = findViewById(R.id.ranking_listview);
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
                                Intent homeIntent = new Intent(RankingActivity.this, HomeActivity.class);
                                startActivity(homeIntent);
                                break;
                            case R.id.dashboard:
                                Intent dashboardIntent = new Intent(RankingActivity.this, RankingActivity.class);
                                startActivity(dashboardIntent);
                                break;
                            case R.id.my_profile:
                                Intent profileIntent = new Intent(RankingActivity.this, ProfileActivity.class);
                                startActivity(profileIntent);
                                break;
                            case R.id.tips:
                                Intent foodIntent = new Intent(RankingActivity.this, TipsActivity.class);
                                startActivity(foodIntent);
                                break;

//                            case R.id.speedo:
//                                Intent speedoIntent = new Intent(RankingActivity.this, Speedo.class);
//                                startActivity(speedoIntent);
//                                break;
                        }
                        item.setChecked(true);
                        mDrawerLayout.closeDrawers();
                        return true;
                    }
                });

        showRanking();

    }


    private void showRanking(){
        String tag_string_req = "req_show_ranking";

        pDialog = new ProgressDialog(RankingActivity.this);
        pDialog.setMessage("Loading data ...");
        showDialog();

        StringRequest strReq = new StringRequest(Request.Method.POST,
                Config_URL.URL_REGISTER,
                new Response.Listener<String>() {

                    @Override
                    public void onResponse(String response) {
                        Log.d(TAG, "Ranking Response: " + response.toString());
                        hideDialog();

                        try {
                            JSONArray jsonArray = new JSONArray(response);
                            ArrayList<Person> persons = new ArrayList<>();
                            for (int i = 0; i < jsonArray.length(); i++){
                                JSONObject obj = jsonArray.getJSONObject(i);
                                persons.add(new Person(i+1, obj.getString("name"), obj.getString("fat")));
                            }

                            PersonsAdapter adapter = new PersonsAdapter(RankingActivity.this, persons);
                            listView.setAdapter(adapter);
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }

                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e(TAG, "Registration Error: " + error.getMessage());
                Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_LONG).show();
                hideDialog();
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                // Posting params to register url
                Map<String, String> params = new HashMap<String, String>();
                params.put("tag", "standing");

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
            Toast.makeText(RankingActivity.this, "Press Back Again to Exit", Toast.LENGTH_SHORT).show();
        }
    }
}
