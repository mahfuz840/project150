package com.the_spartan.run;

import android.content.Context;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.the_spartan.myapplication.R;

import java.util.ArrayList;

public class PersonsAdapter extends ArrayAdapter<Person> {
    public PersonsAdapter(Context context, ArrayList<Person> persons) {
        super(context, 0, persons);
    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {

        View listItemView = convertView;
        if(listItemView == null) {
            listItemView = LayoutInflater.from(getContext()).inflate(
                    R.layout.list_item, parent, false);
        }

        Person currentPerson = getItem(position);
        TextView positionTextView = listItemView.findViewById(R.id.position);
        TextView nameTextView = listItemView.findViewById(R.id.name);
        TextView fatTextView = listItemView.findViewById(R.id.fat);

        positionTextView.setText(String.valueOf(currentPerson.getPosition()));
        nameTextView.setText(currentPerson.getName());
        String fatText = currentPerson.getFat();

        Log.d("TAG", fatText);

        if (fatText.isEmpty() || fatText == null){
            fatTextView.setText("Fat : unavailable");
        } else {
            fatTextView.setText("Fat : " + currentPerson.getFat() + "%");
        }

        return listItemView;
    }
}
