package com.example.cs3141_1.ui.dashboard;

import android.content.Context;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ListView;

import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.example.cs3141_1.Elevator;
import com.example.cs3141_1.ElevatorAdapter;
import com.example.cs3141_1.R;
import com.example.cs3141_1.databinding.FragmentDashboardBinding;
import com.google.android.material.snackbar.Snackbar;

import java.util.ArrayList;
import java.util.Scanner;

public class DashboardFragment extends Fragment {


    private FragmentDashboardBinding binding;
    ListView lv;

    private static Scanner sc;

    //SearchView searchView;
    ArrayAdapter<String> adapter;

    public static int downThreshold = 2;
    private void sharedResponse(String response) {
        SharedPreferences m = PreferenceManager.getDefaultSharedPreferences(this.getContext());
        SharedPreferences.Editor editor = m.edit();
        editor.putString("Response", response);
        editor.commit();
    }

    private static void infoParser(String response){
        sc = new Scanner(response);

        //This (arbitrary) threshold represents the number of reports required to mark an elevator is down


        while(sc.hasNext()){
            String elevatorName;
            String reports;
            String status;
            String id;
            id = sc.next(); //ignore the id
            reports = sc.next();
            elevatorName = sc.next();
            if(Integer.parseInt(reports) >= downThreshold){
                status = "not working";
                
            }else{
                status = "working";
            }
            System.out.println("elevatorName = " + elevatorName);
            elevators.add(new Elevator(id, elevatorName, Integer.parseInt(reports), status));
        }

        System.out.println("after parser called: " + elevators.get(0).getElevatorName());
        System.out.println("elevator array length = " + elevators.size());
        System.out.println("elevator 26 = " + elevators.get(25).getElevatorName());
    }

    public static void infoParser(String response, ArrayList<Elevator> elevators){
        sc = new Scanner(response);

        //This (arbitrary) threshold represents the number of reports required to mark an elevator is down


        while(sc.hasNext()){
            String elevatorName;
            String reports;
            String status;
            String id;
            id = sc.next(); //ignore the id
            reports = sc.next();
            elevatorName = sc.next();
            if(Integer.parseInt(reports) >= downThreshold){
                status = "not working";

            }else{
                status = "working";
            }
            System.out.println("elevatorName = " + elevatorName);
            elevators.add(new Elevator(id, elevatorName, Integer.parseInt(reports), status));
        }
    }

    public void testRead(String response){
        System.out.println("Test read HEY WE'RE PRINTING RESPONSE TO A STRING: " + response);
    }


    static ArrayList<Elevator> elevators = new ArrayList<>();
    public View onCreateView(@NonNull LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        System.out.println("This happens before crash should occur\n");
        View view = inflater.inflate(R.layout.fragment_dashboard, container, false);

        if(getActivity() == null || getContext() == null){

            System.out.println("COULDN'T CREATE");
        }else {
            //getElevators(view);
            String urlString = "https://mtuelevatordown.000webhostapp.com/mobileAPI.php?info=all";

            if(elevators.size() == 0) {
                StringRequest stringRequest = new StringRequest(Request.Method.GET, urlString,
                        new Response.Listener<String>() {
                            @Override
                            public void onResponse(String response) {
                                //infoParser parses the data and adds Elevator objects to the ArrayList<Elevator> according to the parsed data
                                infoParser(response);
                                //sharedResponse(response); BUSTED
                                //Create the listview
                                lv = (ListView) view.findViewById(R.id.listview);
                                ElevatorAdapter adapter = new ElevatorAdapter(getActivity(), R.layout.list_item, elevators);
                                lv.setAdapter(adapter);
                            }

                        },
                        new Response.ErrorListener() {
                            @Override
                            public void onErrorResponse(VolleyError error) {
                                Snackbar.make(getActivity().findViewById(android.R.id.content),
                                        error.toString(), Snackbar.LENGTH_SHORT).show();
                                Log.w("error", error.toString());
                            }
                        });
                //BUSTED CODE, will probably delete
                //This allows us to access the data acquired from GET request. mResponse = the data we care about
                //SharedPreferences m = PreferenceManager.getDefaultSharedPreferences(this.getActivity());
                //String mResponse = m.getString("Response", "");

                //infoParser parses the data and adds Elevator objects to the ArrayList<Elevator> according to the parsed data
                //infoParser(mResponse);

                RequestQueue requestQueue = Volley.newRequestQueue(view.getContext());
                requestQueue.add(stringRequest);
            }
            else{
                lv = (ListView) view.findViewById(R.id.listview);
                ElevatorAdapter adapter = new ElevatorAdapter(getActivity(), R.layout.list_item, elevators);
                lv.setAdapter(adapter);
            }

            //System.out.println("after parser called: " + elevators.get(0).getElevatorName());
            /*
            lv = (ListView) view.findViewById(R.id.listview);
            ElevatorAdapter adapter = new ElevatorAdapter(this.getActivity(), R.layout.list_item, elevators);
            lv.setAdapter(adapter);

             */
        }
        System.out.println("OUTSIDE elevator array length = " + elevators.size());
        return view;
    }

    public static void getElevators(View view){
        String urlString = "https://mtuelevatordown.000webhostapp.com/mobileAPI.php?info=all";

        StringRequest stringRequest = new StringRequest(Request.Method.GET, urlString,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        //infoParser parses the data and adds Elevator objects to the ArrayList<Elevator> according to the parsed data
                        infoParser(response);
                    }

                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.w("error", error.toString());
                    }
                });

        RequestQueue requestQueue = Volley.newRequestQueue(view.getContext());
        requestQueue.add(stringRequest);
    }

    public static int getElevatorNums(String elvname, View v){
        int i = 0;
        int reports = -1;
        if(elevators.size() == 0){
            getElevators(v);
            while(i < elevators.size()){
                if(elvname.compareTo(elevators.get(i).getElevatorName()) == 0){
                    reports = elevators.get(i).getNumberOfReports();
                }
                i++;
            }
        }
        else {
            while (i < elevators.size()) {
                if (elvname.compareTo(elevators.get(i).getElevatorName()) == 0) {
                    reports = elevators.get(i).getNumberOfReports();
                }
                i++;
            }
        }
        return reports;
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        binding = null;
    }
}