package com.the_spartan.run.activities;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.the_spartan.myapplication.R;
import com.the_spartan.run.MainActivity;
import com.the_spartan.run.Speedo;
import com.the_spartan.run.helper.SQLiteHandler;
import com.the_spartan.run.login.LoginButton;
import com.the_spartan.run.volley.AppController;
import com.the_spartan.run.volley.Config_URL;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class CompleteFormActivity extends AppCompatActivity {
    
    private static final String TAG = "CompleteFormActivity";

    private EditText etFullName;
    private EditText etAge;
    private EditText etHeight;
    private EditText etWeight;
    private EditText etWaist;
    private EditText etNeck;
    private Button btnSubmit;
    
    private SQLiteHandler db;
    private Context context;
    
    private ProgressDialog pDialog;
    
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_complete_form);
        getSupportActionBar().setTitle(R.string.complete_form_activity_name);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        
        context = CompleteFormActivity.this;
        
        etFullName = findViewById(R.id.et_full_name);
        etAge = findViewById(R.id.et_age);
        etHeight = findViewById(R.id.et_height);
        etWeight = findViewById(R.id.et_weight);
        etWaist = findViewById(R.id.et_waist);
        etNeck = findViewById(R.id.et_neck);
        
        btnSubmit = findViewById(R.id.btn_submit_full_form);
        btnSubmit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String name = etFullName.getText().toString();
                String age = etAge.getText().toString();
                String height = etHeight.getText().toString();
                String weight = etWeight.getText().toString();
                String waist = etWaist.getText().toString();
                String neck = etNeck.getText().toString();

                if (!name.isEmpty() && !age.isEmpty() && !height.isEmpty() && !weight.isEmpty() && !waist.isEmpty() && !neck.isEmpty()){
                    uploadData(name, age, height, weight, waist, neck);
                } else {
                    Toast.makeText(context, "Please fillup all the data", Toast.LENGTH_SHORT)
                            .show();
                }
            }
        });
    }
    
    private void uploadData(final String name, final String age, final String height, final String weight, final String waist, final String neck){
        String tag_string_req = "req_update_basic";
        
        db = new SQLiteHandler(context);
        HashMap<String, String> user = db.getUserDetails();
        String email = user.get("email");
        String uid = user.get("uid");

        Log.d(TAG, uid);

        pDialog = new ProgressDialog(context);
        pDialog.setMessage("Uploading data ...");
        showDialog();

        StringRequest strReq = new StringRequest(Request.Method.POST,
                Config_URL.URL_REGISTER,
                new Response.Listener<String>() {

                    @Override
                    public void onResponse(String response) {
                        Log.d(TAG, "Update Basic Response: " + response.toString());
                        hideDialog();

                        try {
                            JSONObject jObj = new JSONObject(response);
                            boolean error = jObj.getBoolean("error");
                            if (!error) {
                                // User successfully stored in MySQL
                                // Now store the user in sqlite

                                AlertDialog.Builder alertDialog = new AlertDialog.Builder(context, R.style.MyAlertDialogTheme);
                                alertDialog.setTitle("Update Successful!")
                                        .setMessage("Tap OK to continue")
                                        .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                                            @Override
                                            public void onClick(DialogInterface dialogInterface, int i) {
                                                Intent speedoIntent = new Intent(context, Speedometer.class);
                                                startActivity(speedoIntent);
                                            }
                                        });
                                alertDialog.setCancelable(false);
                                alertDialog.show();

                                // Launch login activity

                            } else {

                                // Error occurred in registration. Get the error
                                // message
                                String errorMsg = jObj.getString("error_msg");
                                Log.d("JSON", "ERROR"+errorMsg);
                                Toast.makeText(context, errorMsg, Toast.LENGTH_LONG).show();
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }

                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e(TAG, "Registration Error: " + error.getMessage());
                Toast.makeText(context, error.getMessage(), Toast.LENGTH_LONG).show();
                hideDialog();
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                // Posting params to register url
                Map<String, String> params = new HashMap<String, String>();
                params.put("tag", "update_basic");
                params.put("email", email);
                params.put("name", name);
                params.put("age", age);
                params.put("height", height);
                params.put("weight", weight);
                params.put("uid", uid);
                params.put("waist", waist);
                params.put("neck", neck);

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
        Intent homeIntent = new Intent(CompleteFormActivity.this, HomeActivity.class);
        startActivity(homeIntent);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        if (item.getItemId() == android.R.id.home){
            onBackPressed();
        }
        return true;
    }
}
