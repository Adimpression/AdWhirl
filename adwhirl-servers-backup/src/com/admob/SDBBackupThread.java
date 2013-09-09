package com.admob;

import java.io.FileWriter;
import java.util.List;

import org.json.JSONWriter;

import com.amazonaws.services.simpledb.AmazonSimpleDB;
import com.amazonaws.services.simpledb.model.Attribute;
import com.amazonaws.services.simpledb.model.Item;
import com.amazonaws.services.simpledb.model.SelectRequest;
import com.amazonaws.services.simpledb.model.SelectResult;

public class SDBBackupThread implements Runnable {
	private AmazonSimpleDB sdb;
	private String domain;
	private String dateString;

	public SDBBackupThread(String domain, String dateString) {
		this.domain = domain;
		this.dateString = dateString;
		sdb = Util.getSDB();
	}

	public void run() {
		try {
			FileWriter jsonWriter;
			jsonWriter = new FileWriter("sdb-" + domain + "-" + dateString + ".json");
			JSONWriter json = new JSONWriter(jsonWriter);
			json = json.object();
			json = json.key(domain);
			json = json.array();
			
			String nextToken = null;
			do {
				SelectRequest request = new SelectRequest("select * from `" + domain + "`");
				request.setNextToken(nextToken);
				try {
					SelectResult result = sdb.select(request);
					nextToken = result.getNextToken();
					List<Item> items = result.getItems();

					for(Item item : items) {
						String itemName = item.getName();

						json = json.object();
						json = json.key(itemName);
						
						json = json.object();
						
						List<Attribute> attributes = item.getAttributes();
						for(Attribute attribute : attributes) {
							String attributeName = attribute.getName();
							String attributeValue = attribute.getValue();
							
							json = json.key(attributeName);
							json = json.value(attributeValue);
						}
						
						json = json.endObject();
						json = json.endObject();
					}
				}
				catch(Exception e) {
					e.printStackTrace();
				}
			}
			while(nextToken != null);
			
			json = json.endArray();
			json = json.endObject();
			jsonWriter.close();
		}
		catch (Exception e1) {
			e1.printStackTrace();
		}
	}
}
