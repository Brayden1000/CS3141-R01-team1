package com.example.cs3141_1.ui.home;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.example.cs3141_1.MainActivity;
import com.example.cs3141_1.R;
import com.example.cs3141_1.databinding.FragmentHomeBinding;
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
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.HashMap;
import java.util.Map;

public class HomeFragment extends Fragment {

    GoogleSignInClient mGoogleSignInClient;

    private FragmentHomeBinding binding;

    SignInButton signin;
    Button post;
    Button request;

    String urlString = "https://mtuelevatordown.000webhostapp.com/mobileAPI.php";

    GoogleSignInAccount account;

    public static String emailAddress;

    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {

        HomeViewModel homeViewModel =
                new ViewModelProvider(this).get(HomeViewModel.class);

        binding = FragmentHomeBinding.inflate(inflater, container, false);
        View root = binding.getRoot();

        //Google stuff
        GoogleSignInOptions gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                .requestEmail()
                .build();

        mGoogleSignInClient = GoogleSignIn.getClient(getContext(), gso);

        account = GoogleSignIn.getLastSignedInAccount(getContext());

        //Button
        signin = (SignInButton) root.findViewById(R.id.sign_in_button);

        post = (Button) root.findViewById(R.id.post);
        request = (Button) root.findViewById(R.id.request);

        post.setVisibility(View.INVISIBLE);
        request.setVisibility(View.INVISIBLE);

        emailAddress = "cooluser@mtu.edu";

        if(account != null && MainActivity.getData() == true){
            signin.setVisibility(View.INVISIBLE);
            emailAddress = account.getEmail();
        }
        else {
            signin.setVisibility(View.VISIBLE);
        }

       // URL to call
        String data = "test"; //data to post

        post.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                StringRequest stringRequest = new StringRequest(Request.Method.POST, urlString,
                        new Response.Listener<String>() {
                            @Override
                            public void onResponse(String response) {
                                Snackbar.make(getActivity().findViewById(android.R.id.content),
                                        "success", Snackbar.LENGTH_SHORT).show();
                                Log.w("success", response);
                            }
                        },
                        new Response.ErrorListener() {
                            @Override
                            public void onErrorResponse(VolleyError error) {
                                Snackbar.make(getActivity().findViewById(android.R.id.content),
                                        error.toString(), Snackbar.LENGTH_SHORT).show();
                                Log.w("error",  error.toString());
                            }
                        }) {

                    @Override
                    protected Map<String, String> getParams() {
                        Map<String, String> params = new HashMap<String, String>();
                        params.put("data", data);

                        return params;
                    }
                };

                RequestQueue requestQueue = Volley.newRequestQueue(view.getContext());
                requestQueue.add(stringRequest);
            }
        });

        request.setOnClickListener(new View.OnClickListener() {
            String urlString = "https://mtuelevatordown.000webhostapp.com/mobileAPI.php?info=all";
            @Override
            public void onClick(View view) {
                StringRequest stringRequest = new StringRequest(Request.Method.GET, urlString,
                        new Response.Listener<String>() {
                            @Override
                            public void onResponse(String response) {
                                //Snackbar.make(getActivity().findViewById(android.R.id.content),
                                        //"success", Snackbar.LENGTH_SHORT).show();
                                Log.w("success", response.trim());
                                //int t = Integer.parseInt(response.trim());
                            }
                        },
                        new Response.ErrorListener() {
                            @Override
                            public void onErrorResponse(VolleyError error) {
                                Snackbar.make(getActivity().findViewById(android.R.id.content),
                                        error.toString(), Snackbar.LENGTH_SHORT).show();
                                Log.w("error",  error.toString());
                            }
                        });

                RequestQueue requestQueue = Volley.newRequestQueue(view.getContext());
                requestQueue.add(stringRequest);
            }
        });

        ConfignewButton1();

        return root;


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