package com.example.cs3141_1;

public class Elevator {
    private String elevatorName;
    private int numberOfReports;
    private String officialStatus;

    public Elevator(String elevatorName, int numberOfReports, String officialStatus){
        this.elevatorName = elevatorName;
        this.numberOfReports = numberOfReports;
        this.officialStatus = officialStatus;
    }

    public int getNumberOfReports() {
        return numberOfReports;
    }

    public String getElevatorName() {
        return elevatorName;
    }

    public String getOfficialStatus() {
        return officialStatus;
    }
}
