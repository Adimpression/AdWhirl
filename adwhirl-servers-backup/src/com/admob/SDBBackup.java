package com.admob;

import java.io.File;
import java.io.IOException;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import com.amazonaws.services.s3.AmazonS3;

public class SDBBackup {
	public static void main(String[] args) throws IOException, InterruptedException {
		DateFormat dateFormat = new SimpleDateFormat("yyyy_MM_dd");
		Date date = new java.util.Date();
		String dateString = dateFormat.format(date);

		StringBuffer filesToTar = new StringBuffer();
		List<Thread> threads = new ArrayList<Thread>();

		for(String domain : Util.DOMAINS) {
			Thread thread = new Thread(new SDBBackupThread(domain, dateString));
			threads.add(thread);
			thread.start();
			filesToTar.append("sdb-" + domain + "-" + dateString + ".json ");
		}

		for(Thread thread : threads) {
			try {
				thread.join();
			} 
			catch(InterruptedException e) {
				e.printStackTrace();
			}
		}
		
		Runtime runtime = Runtime.getRuntime();
		
		String tarFileName = "sdb-backup-" + dateString + ".tar.gz";
		String tarCommand = "tar -czf " + tarFileName + " " + filesToTar;

		Process process = runtime.exec(tarCommand);
		process.waitFor();

		File tarFile = new File(tarFileName);
		AmazonS3 s3 = Util.getS3();
		s3.putObject("adwhirl-sdb-backups", tarFileName, tarFile);

		String rmCommand = "rm -f " + tarFileName + " " + filesToTar;
		process = runtime.exec(rmCommand);
		process.waitFor();
	}
}
