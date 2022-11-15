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

    private Scanner sc;

    //SearchView searchView;
    ArrayAdapter<String> adapter;

    public static int downThreshold = 2;
    private void sharedResponse(String response) {
        SharedPreferences m = PreferenceManager.getDefaultSharedPreferences(this.getActivity());
        SharedPreferences.Editor editor = m.edit();
        editor.putString("Response", response);
        editor.commit();
    }

    private void infoParser(String response){
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
            elevators.add(new Elevator(id, elevatorName, Integer.parseInt(reports), status));
        }


    }


    ArrayList<Elevator> elevators = new ArrayList<>();
    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_dashboard, container, false);

        if(this.getActivity() == null){

            System.out.println("COULDN'T CREATE");
        }else {

            String urlString = "https://mtuelevatordown.000webhostapp.com/mobileAPI.php?info=all";

            StringRequest stringRequest = new StringRequest(Request.Method.GET, urlString,
                    new Response.Listener<String>() {
                        @Override
                        public void onResponse(String response) {
                            sharedResponse(response);
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
            //This allows us to access the data acquired from GET request. mResponse = the data we care about
            SharedPreferences m = PreferenceManager.getDefaultSharedPreferences(this.getActivity());
            String mResponse = m.getString("Response", "");

            //infoParser parses the data and adds Elevator objects to the ArrayList<Elevator> according to the parsed data
            infoParser(mResponse);

            RequestQueue requestQueue = Volley.newRequestQueue(view.getContext());
            requestQueue.add(stringRequest);

            lv = (ListView) view.findViewById(R.id.listview);
            ElevatorAdapter adapter = new ElevatorAdapter(this.getActivity(), R.layout.list_item, elevators);
            lv.setAdapter(adapter);
        }
        return view;
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        binding = null;
    }
}