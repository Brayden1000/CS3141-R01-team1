package com.example.cs3141_1;

public class Elevator {
    private String id;
    private String elevatorName;
    private int numberOfReports;
    private String officialStatus;

    public Elevator(String id, String elevatorName, int numberOfReports, String officialStatus){
        this.id = id;
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

    public String getId() { return id; }

    public void setElevatorName(String elevatorName) {
        this.elevatorName = elevatorName;
    }
    public void setNumberOfReports(int numberOfReports) {
        this.numberOfReports = numberOfReports;
    }
    public void setOfficialStatus(String officialStatus) {
        this.officialStatus = officialStatus;
    }
}
