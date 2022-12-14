package com.example.cs3141_1.ui.home;

import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.ScaleGestureDetector;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.appcompat.widget.AppCompatTextView;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.example.cs3141_1.Elevator;
import com.example.cs3141_1.MainActivity;
import com.example.cs3141_1.R;
import com.example.cs3141_1.databinding.FragmentHomeBinding;
import com.example.cs3141_1.ui.dashboard.DashboardFragment;
import com.google.android.gms.auth.api.signin.GoogleSignIn;
import com.google.android.gms.auth.api.signin.GoogleSignInAccount;
import com.google.android.gms.auth.api.signin.GoogleSignInClient;
import com.google.android.gms.auth.api.signin.GoogleSignInOptions;
import com.google.android.gms.common.SignInButton;
import com.google.android.gms.common.api.ApiException;
import com.google.android.gms.tasks.Task;
import com.google.android.material.snackbar.Snackbar;

import java.io.BufferedOutputStream;
import java.io.BufferedWriter;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.io.StringReader;
import java.lang.reflect.Field;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class HomeFragment extends Fragment {


    GoogleSignInClient mGoogleSignInClient;
    ScaleGestureDetector scaleGestureDetector;
    private FragmentHomeBinding binding;

    SignInButton signin;
    ImageView image;

    //Elevator buttons
    Button sdc;
    Button forestry;

    String urlString = "https://mtuelevatordown.000webhostapp.com/mobileAPI.php";

    GoogleSignInAccount account;

    public static String emailAddress;
    public static View root;

    TextView horizontalText;

    ArrayList<Elevator> elevators = new ArrayList<>();
    ArrayList<Elevator> brokenElevators = new ArrayList<>();
    boolean alreadyLoaded = false;

    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {


        HomeViewModel homeViewModel =
                new ViewModelProvider(this).get(HomeViewModel.class);

        binding = FragmentHomeBinding.inflate(inflater, container, false);
        root = binding.getRoot();

        //Google stuff
        GoogleSignInOptions gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                .requestEmail()
                .build();

        mGoogleSignInClient = GoogleSignIn.getClient(getContext(), gso);

        account = GoogleSignIn.getLastSignedInAccount(getContext());

        //google button
        signin = (SignInButton) root.findViewById(R.id.sign_in_button);
        image = root.findViewById(R.id.map);

        emailAddress = "cooluser@mtu.edu";

        if(account != null && MainActivity.getData() == true){
            signin.setVisibility(View.INVISIBLE);
            emailAddress = account.getEmail();
        }
        else {
            signin.setVisibility(View.VISIBLE);
        }

        horizontalText = root.findViewById(R.id.horizontalText);
        horizontalText.setSelected(true);
        getElevators(root);


        //Elevator buttons
        sdc = (Button) root.findViewById(R.id.sdc);
        sdc.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                int t =0;
                for (int i = 0; i < elevators.size(); i++) {
                    if (elevators.get(i).getElevatorName().compareTo("Student_Development_Complex")==0) {
                        Snackbar.make(getActivity().findViewById(android.R.id.content), "Student Development Complex: "  + elevators.get(i).getNumberOfReports(), Snackbar.LENGTH_SHORT).show();
                        break;
                    }

                }
            }
        });

        forestry = (Button) root.findViewById(R.id.forestry);
        forestry.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                int t =0;
                for (int i = 0; i < elevators.size(); i++) {
                    if (elevators.get(i).getElevatorName().compareTo("Forestry_Building")==0) {
                        Snackbar.make(getActivity().findViewById(android.R.id.content), "Forestry Building: "  + elevators.get(i).getNumberOfReports(), Snackbar.LENGTH_SHORT).show();
                        break;
                    }

                }
            }
        });

        ConfignewButton1();

        return root;
    }


    public void getElevators(View view){
        String urlString = "https://mtuelevatordown.000webhostapp.com/mobileAPI.php?info=all";

        StringRequest stringRequest = new StringRequest(Request.Method.GET, urlString,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {

                        DashboardFragment.infoParser(response, elevators);
                        String brokenElevatorString = "";
                        if(alreadyLoaded == false) {
                            //prevents from adding to the list of broken elevators more than once per session
                            alreadyLoaded = true;
                            for (int i = 0; i < elevators.size(); i++) {
                                if (elevators.get(i).getOfficialStatus().equals("not working")) {
                                    brokenElevators.add(elevators.get(i));
                                }
                            }
                        }
                        for(int i = 0; i < brokenElevators.size(); i++){
                            brokenElevatorString += brokenElevators.get(i).getElevatorName() + ",    ";
                        }


                        horizontalText.setText(brokenElevatorString);

                        /*
                        Animation animation = AnimationUtils.loadAnimation(getContext(), R.anim.anim);
                        horizontalText.startAnimation(animation);
                        */


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

    private int getReport(String elevator_name){
        //get the amount of reports for that elevator
        return DashboardFragment.getElevatorNums(elevator_name, root);
    }

    private void ConfignewButton1(){
        signin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                switch (v.getId()) {
                    case R.id.sign_in_button:
                        signIn();

                        break;
                    // ...
                }
            }
        });
    }


    private void signIn() {
        Intent signInIntent = mGoogleSignInClient.getSignInIntent();
        startActivityForResult(signInIntent, 1);
        //startActivity
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        // Result returned from launching the Intent from GoogleSignInClient.getSignInIntent(...);
        if (requestCode == 1) {
            // The Task returned from this call is always completed, no need to attach
            // a listener.
            Task<GoogleSignInAccount> task = GoogleSignIn.getSignedInAccountFromIntent(data);
            handleSignInResult(task);
        }
    }

    private void handleSignInResult(Task<GoogleSignInAccount> completedTask) {
        try {
            String email = completedTask.getResult().getEmail().substring(completedTask.getResult().getEmail().indexOf("@")+1, completedTask.getResult().getEmail().indexOf("@")+4);
            if(!email.equals("mtu")){
                signin.setVisibility(View.VISIBLE);
                Snackbar.make(getActivity().findViewById(android.R.id.content), "Please login with a mtu email", Snackbar.LENGTH_SHORT).show();
                account = null;
                mGoogleSignInClient.signOut();
                MainActivity.setData(false);
            }
            else {
                account = completedTask.getResult(ApiException.class);
                MainActivity.setData(true);
                Log.w("Email", account.getEmail());
                signin.setVisibility(View.INVISIBLE);
                Snackbar.make(getActivity().findViewById(android.R.id.content),
                        "Successfully logged in", Snackbar.LENGTH_SHORT).show();

                StringRequest stringRequest = new StringRequest(Request.Method.POST, urlString,
                        new Response.Listener<String>() {
                            @Override
                            public void onResponse(String response) {
                                //Snackbar.make(getActivity().findViewById(android.R.id.content),
                                        //"success", Snackbar.LENGTH_SHORT).show();
                                Log.w("success", response);
                            }
                        },
                        new Response.ErrorListener() {
                            @Override
                            public void onErrorResponse(VolleyError error) {
                                //Snackbar.make(getActivity().findViewById(android.R.id.content),
                                        //error.toString(), Snackbar.LENGTH_SHORT).show();
                                //Log.w("error",  error.toString());
                            }
                        }) {

                    @Override
                    protected Map<String, String> getParams() {
                        Map<String, String> params = new HashMap<String, String>();
                        params.put("login", account.getEmail());

                        return params;
                    }
                };

                RequestQueue requestQueue = Volley.newRequestQueue(getContext());
                requestQueue.add(stringRequest);

            }


            //mySnackbar.show();
            // Signed in successfully, show authenticated UI.
           // Intent switchActivityIntent = new Intent(this, MainActivity.class);
            //startActivity(switchActivityIntent);
        } catch (ApiException e) {
            // The ApiException status code indicates the detailed failure reason.
            // Please refer to the GoogleSignInStatusCodes class reference for more information.
            Log.w("Fail", "signInResult:failed code=" + e.getStatusCode());
            //updateUI(null);
        }
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        binding = null;
    }
}