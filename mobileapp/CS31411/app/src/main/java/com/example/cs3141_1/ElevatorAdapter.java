package com.example.cs3141_1;

import android.app.Activity;
import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.TextView;

import androidx.annotation.NonNull;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.example.cs3141_1.ui.dashboard.DashboardFragment;
import com.example.cs3141_1.ui.home.HomeFragment;

import java.util.ArrayList;
import java.util.Scanner;


public class ElevatorAdapter extends ArrayAdapter<Elevator> {

    private static final String TAG = "ElevatorListAdapter";

    private Context mContext;
    int mResource;

    private boolean checkExistingReport(String reports, String email){
        Scanner sc = new Scanner(reports);
        while(sc.hasNext()){
            //If the email address already exists within the reports
           if(sc.next().toString().compareTo(email) == 0){
               return true;
           }
        }
        return false;
    }

    public ElevatorAdapter(@NonNull Context context, int resource, @NonNull ArrayList<Elevator> objects) {
        super(context, resource, objects);
        mContext = context;
        mResource = resource;
    }

    public View getView(int position, View convertView, ViewGroup parent){
        String elevatorName = getItem(position).getElevatorName();
        int elevatorReports = getItem(position).getNumberOfReports();
        String elevatorStatus = getItem(position).getOfficialStatus();
        String id = getItem(position).getId();

        Elevator elevator = new Elevator(id, elevatorName, elevatorReports, elevatorStatus);

        LayoutInflater inflater = LayoutInflater.from(mContext);
        convertView = inflater.inflate(mResource, parent, false);

        TextView tvName = (TextView) convertView.findViewById(R.id.elevatorName);
        TextView tvReports = (TextView) convertView.findViewById(R.id.numberOfReports);
        TextView tvStatus = (TextView) convertView.findViewById(R.id.status);

        tvName.setText(elevatorName);
        tvReports.setText("Reports: " + String.valueOf(elevatorReports));
        tvStatus.setText("Status: " + elevatorStatus);
        Button reportBtn = (Button) convertView.findViewById(R.id.reportBtn);
        RequestQueue requestQueue = Volley.newRequestQueue(convertView.getContext());
        reportBtn.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                if(HomeFragment.emailAddress != null){
                    String URL = "https://mtuelevatordown.000webhostapp.com/mobileAPI.php?viewReports=" + id.toString();
                    StringRequest stringRequest = new StringRequest(Request.Method.GET, URL,
                            new Response.Listener<String>() {
                                @Override
                                public void onResponse(String response) {
                                    Log.w("success", response.trim());
                                    //if the user has NOT already reported the elevator, send report
                                    if(!checkExistingReport(response, HomeFragment.emailAddress)){
                                        Report report = new Report(id, HomeFragment.emailAddress);
                                        report.send(requestQueue);
                                        System.out.println("USER " + HomeFragment.emailAddress + " IS REPORTING ELEVATOR " + elevatorName + " ID # " + id);
                                    }else{
                                        System.out.println("THIS USER HAS ALREADY REPORTED THIS ELEVATOR");
                                    }
                                }
                            },
                            new Response.ErrorListener() {
                                @Override
                                public void onErrorResponse(VolleyError error) {
                                    Log.w("error",  error.toString());
                                }
                            });

                    requestQueue.add(stringRequest);
                }else{
                    System.out.println("Shit's null my guy");
                }
            }
        });

        return convertView;
    }
}
