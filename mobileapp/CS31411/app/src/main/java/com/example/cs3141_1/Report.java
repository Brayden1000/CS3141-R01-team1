package com.example.cs3141_1;

import android.util.Log;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

/**
 * Report class
 * Simply put correct values into the constructor, and then call .send()
 */
public class Report {

    // Attributes
    private final int elevatorID;
    private final String reporterEmail;
    private final String comment;

    /**
     * First constructor, takes an int id and the email, comment left out
     * @param id    : elevator id
     * @param email : reporter email
     */
    public Report(int id, String email) {

        this.elevatorID = id;
        this.reporterEmail = email;
        this.comment = "";

    }

    /**
     * Constructor, takes an int id, email, and comment
     * @param id      : elevator id
     * @param email   : reporter email
     * @param comment : given comment
     */
    public Report(int id, String email, String comment) {

        this.elevatorID = id;
        this.reporterEmail = email;
        this.comment = comment;

    }

    /**
     * Constructor, takes an String id and the email, comment left out
     * Just reformats the id as an int and sends it through the other constructor
     * If you get an error because of the parseInt then you are sending an invalid ID
     * @param id    : elevator id
     * @param email : reporter email
     */
    public Report(String id, String email) {

        this(Integer.parseInt(id), email);

    }

    /**
     * Constructor, takes an String id, email, and comment
     * Just reformats the id as an int and sends it through the other constructor
     * If you get an error because of the parseInt then you are sending an invalid ID
     * @param id    : elevator id
     * @param email : reporter email
     */
    public Report(String id, String email, String comment) {

        this(Integer.parseInt(id), email, comment);

    }

    /**
     * Used in the send method, formatting the String into the correct header to attach to the URL
     * @return the end of the URL from mobileAPI.php and including the GET request
     */
    private String formatHeader() {

        String header = String.format("mobileAPI.php?report=1&elevatorID=%s&email=%s",
                                       elevatorID, reporterEmail);
        if (comment != null && !comment.equals("")) {
            header = header + String.format("&comment=%s", comment);
        }
        // Remove spaces from the URL, replacing them with the space identifier of %20
        header = header.replaceAll(" ", "%20");
        return header;

    }

    /**
     * Sends the GET request
     * @param requestQueue : The RequestQueue to put this request into.
     */
    public void send(RequestQueue requestQueue) {

        String URL = "https://mtuelevatordown.000webhostapp.com/" + formatHeader();
        System.out.println("THE URL IS " + URL);
        StringRequest stringRequest = new StringRequest(Request.Method.GET, URL,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.w("success", response.trim());
                        System.out.println("THIS SHOULD BE WORKING");
                    }

                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.w("error",  error.toString());
                    }
                });

        requestQueue.add(stringRequest);

    }

}