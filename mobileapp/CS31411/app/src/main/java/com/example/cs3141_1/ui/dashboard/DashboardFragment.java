package com.example.cs3141_1.ui.dashboard;

import android.content.Context;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ListView;

import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;

import com.example.cs3141_1.Elevator;
import com.example.cs3141_1.ElevatorAdapter;
import com.example.cs3141_1.R;
import com.example.cs3141_1.databinding.FragmentDashboardBinding;

import java.util.ArrayList;
import java.util.List;

public class DashboardFragment extends Fragment {

    private FragmentDashboardBinding binding;
    ListView lv;

    //SearchView searchView;
    ArrayAdapter<String> adapter;
    String[] data = {"okay", "please work", "im dying","okay", "please work", "im dying","okay", "please work", "im dying","okay", "please work", "im dying","okay", "please work", "im dying","okay", "please work", "im dying","okay", "please work", "im dying"};

    ArrayList<Elevator> elevators = new ArrayList<>();
    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {

        //Hard code elevators here for now
        Elevator e1 = new Elevator("Elevator1", 0, "working");
        Elevator e2 = new Elevator("Elevator2", 20, "not working");
        Elevator e3 = new Elevator("Elevator3", 3, "working");
        Elevator e4 = new Elevator("Elevator4", 0, "working");
        Elevator e5 = new Elevator("Elevator5", 0, "working");
        Elevator e6 = new Elevator("Elevator6", 31, "not working");
        Elevator e7 = new Elevator("Elevator7", 1, "working");
        Elevator e8 = new Elevator("Elevator8", 0, "working");
        Elevator e9 = new Elevator("Elevator9", 52, "not working");
        Elevator e10 = new Elevator("Elevator10", 34, "not working");
        Elevator e11 = new Elevator("Elevator11", 0, "working");
        Elevator e12 = new Elevator("Elevator12", 0, "working");

        //Add elevators to list
        elevators.add(e1);
        elevators.add(e2);
        elevators.add(e3);
        elevators.add(e4);
        elevators.add(e5);
        elevators.add(e6);
        elevators.add(e7);
        elevators.add(e8);
        elevators.add(e9);
        elevators.add(e10);
        elevators.add(e11);
        elevators.add(e12);



        /*This works!
        View view = inflater.inflate(R.layout.fragment_dashboard,container,false);
        lv = (ListView) view.findViewById(R.id.listview);
        adapter = new ArrayAdapter<String>(getActivity(), android.R.layout.simple_list_item_1,data);
        lv.setAdapter(adapter);
        */
        View view = inflater.inflate(R.layout.fragment_dashboard,container,false);
        lv = (ListView) view.findViewById(R.id.listview);
        ElevatorAdapter adapter = new ElevatorAdapter(this.getActivity(),R.layout.list_item,elevators);
        lv.setAdapter(adapter);
        return view;
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        binding = null;
    }
}