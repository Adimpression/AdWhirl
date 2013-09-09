package com.admob;

import java.util.ArrayList;
import java.util.List;

import com.amazonaws.auth.BasicAWSCredentials;
import com.amazonaws.services.s3.AmazonS3;
import com.amazonaws.services.s3.AmazonS3Client;
import com.amazonaws.services.simpledb.AmazonSimpleDB;
import com.amazonaws.services.simpledb.AmazonSimpleDBClient;

public class Util {
    public static final List<String> DOMAINS;

    static {
	DOMAINS = new ArrayList<String>();

	DOMAINS.add("app_customs");
	DOMAINS.add("apps");
	DOMAINS.add("networks");
	DOMAINS.add("customs");
	DOMAINS.add("stats");
	DOMAINS.add("users");
    }

    public static final String myAccessKey =
        "CHANGEME";


    public static final String mySecretKey =
        "CHANGEME";


    public static AmazonSimpleDB getSDB() {
	return new AmazonSimpleDBClient(new BasicAWSCredentials(myAccessKey, mySecretKey));
    }

    public static AmazonS3 getS3() {
	return new AmazonS3Client(new BasicAWSCredentials(myAccessKey, mySecretKey));
    }
}
