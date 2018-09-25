package com.the_spartan.run;

import android.animation.Animator;
import android.animation.AnimatorListenerAdapter;
import android.databinding.DataBindingUtil;
import android.support.v4.content.ContextCompat;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;

import com.the_spartan.run.login.FlexibleFrameLayout;
import com.the_spartan.run.login.SignUpFragment;
import com.the_spartan.myapplication.R;
import com.the_spartan.myapplication.databinding.ActivityMainBinding;
import com.the_spartan.run.login.LoginFragment;

import static android.view.View.INVISIBLE;
import static android.view.View.VISIBLE;

public class MainActivity extends AppCompatActivity {
    private static final String TAG = "MainActivity";
    private ActivityMainBinding binding;
    private boolean isLogin = true;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        binding = DataBindingUtil.setContentView(this, R.layout.activity_main);

        LoginFragment topLoginFragment = new LoginFragment();
        SignUpFragment topSignUpFragment = new SignUpFragment();

        getSupportFragmentManager().beginTransaction()
                .replace(R.id.sign_up_fragment, topSignUpFragment)
                .replace(R.id.login_fragment, topLoginFragment)
                .commit();

        binding.loginFragment.setRotation(-90);

        binding.button.setOnSignUpListener(topSignUpFragment);
        binding.button.setOnLoginListener(topLoginFragment);

        binding.button.setOnButtonSwitched(isLogin -> {
            binding.getRoot()
                    .setBackgroundColor(ContextCompat.getColor(
                            this,
                            isLogin ? R.color.colorPrimary : R.color.secondPage));
        });

        binding.loginFragment.setVisibility(VISIBLE);
    }

    @Override
    public void onWindowFocusChanged(boolean hasFocus) {
        super.onWindowFocusChanged(hasFocus);

        binding.signUpFragment.setPivotX(binding.signUpFragment.getWidth() / 2);
        binding.signUpFragment.setPivotY(binding.signUpFragment.getHeight());

        binding.loginFragment.setPivotX(binding.loginFragment.getWidth() / 2);
        binding.loginFragment.setPivotY(binding.loginFragment.getHeight());
    }

    public void switchFragment(View v) {
        if (isLogin) {
            binding.loginFragment.setVisibility(VISIBLE);
            binding.loginFragment.animate().rotation(0).setListener(new AnimatorListenerAdapter() {
                @Override
                public void onAnimationEnd(Animator animation) {
                    super.onAnimationEnd(animation);
                    binding.signUpFragment.setVisibility(INVISIBLE);
                    binding.signUpFragment.setRotation(90);
                    binding.wrapper.setDrawOrder(FlexibleFrameLayout.ORDER_LOGIN_STATE);
                }
            });
        } else {
            binding.signUpFragment.setVisibility(VISIBLE);
            binding.signUpFragment.animate().rotation(0).setListener(new AnimatorListenerAdapter() {
                @Override
                public void onAnimationEnd(Animator animation) {
                    super.onAnimationEnd(animation);
                    binding.loginFragment.setVisibility(INVISIBLE);
                    binding.loginFragment.setRotation(-90);
                    binding.wrapper.setDrawOrder(FlexibleFrameLayout.ORDER_SIGN_UP_STATE);
                }
            });
        }

        isLogin = !isLogin;
        binding.button.startAnimation();
    }

}
