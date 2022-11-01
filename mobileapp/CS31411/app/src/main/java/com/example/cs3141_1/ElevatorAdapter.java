package com.example.cs3141_1;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import androidx.annotation.NonNull;

import com.example.cs3141_1.ui.dashboard.DashboardFragment;

import java.util.ArrayList;


public class ElevatorAdapter extends ArrayAdapter<Elevator> {

    private static final String TAG = "ElevatorListAdapter";

    private Context mContext;
    int mResource;

    public ElevatorAdapter(@NonNull Context context, int resource, @NonNull ArrayList<Elevator> objects) {
        super(context, resource, objects);
        mContext = context;
        mResource = resource;
    }

    public View getView(int position, View convertView, ViewGroup parent){
        String elevatorName = getItem(position).getElevatorName();
        int elevatorReports = getItem(position).getNumberOfReports();
        String elevatorStatus = getItem(position).getOfficialStatus();

        Elevator elevator = new Elevator(elevatorName, elevatorReports, elevatorStatus);

        LayoutInflater inflater = LayoutInflater.from(mContext);
        convertView = inflater.inflate(mResource, parent, false);

        TextView tvName = (TextView) convertView.findViewById(R.id.elevatorName);
        TextView tvReports = (TextView) convertView.findViewById(R.id.numberOfReports);
        TextView tvStatus = (TextView) convertView.findViewById(R.id.status);

        tvName.setText(elevatorName);
        tvReports.setText("Reports: " + String.valueOf(elevatorReports));
        tvStatus.setText("Status: " + elevatorStatus);

        return convertView;
    }
}
