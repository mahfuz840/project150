package com.the_spartan.run;

public class Person {
    private int position;
    private String name;
    private String fat;

    public Person(int position, String name, String fat){
        this.position = position;
        this.name = name;
        this.fat = fat;
    }

    public int getPosition() {
        return position;
    }

    public String getName() {
        return name;
    }

    public String getFat() {
        return fat;
    }
}
