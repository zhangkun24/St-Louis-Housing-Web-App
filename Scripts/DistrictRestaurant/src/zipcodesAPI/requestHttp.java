package zipcodesAPI;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.Charset;


import org.json.JSONObject;
import org.json.JSONArray;
import org.json.JSONException;

import javax.net.ssl.HttpsURLConnection;

// the format for the district table
/*
 *  ZipCode		 MalePop	FemalePop	AverageHouseValue	IncomePerHousehold		MedianAge		AverageFamilySize 	AreaLand   AreaWater
 *  63130  		14240		45844		203900.00			59957.00				34.60			2.94				5.036000     0.000000 	
 */
//format for district_has_race table
/*
 * ZipCode	WhitePop
 * 63130	16087
 * 
 * ZipCode	BlackPop
 * 63130	12678
 * 
 * ZipCode	HispanicPop
 * 63130	787
 *
 * ZipCode	AsianPop
 * 63130	1582
 * 
 * ZipCode	IndianPop
 * 63130	306
 * 
 * ZipCode	HawaiianPop
 * 63130	27
 * 
 * 
 * 
 * 
 */

public class requestHttp {
	
	public static void main(String[] args) throws Exception{
		
		//63147, 63120, 63115, 63112, 63113, 63107, 63108, 63106, 63110, 63103, 
		//63101, 63102, 63104, 63139, 63118, 63109, 63116, 63111
		 String[] zipArr = {"63147", "63120", "63115", "63112", "63113", "63107", "63108", "63106", "63110",
				 			"63103", "63101", "63102", "63104", "63139", "63118", "63109", "63116", "63111"};
		
		 for(int i = 0; i < zipArr.length; ++i){
			 
			 // make a new instance 
			 requestHttp http = new requestHttp();
			 
			 // make the url string
			 String urlPartOne = "http://api.zip-codes.com/ZipCodesAPI.svc/1.0/GetZipCodeDetails/";
			 String urlPartTwo = zipArr[i];  // this part store the zipcode part
			 String urlPartThree = "?key=UD9L6POEZX38R3GUQQ3D";
			 
			 // get the item object according to the API
			 String rawString = http.getStringFromUrl(urlPartOne + urlPartTwo + urlPartThree);
			 String jsonString = rawString.substring(3, rawString.length());
			 JSONObject jsonObject = http.getJson(jsonString);
			 JSONObject jsonItemObject = (JSONObject)jsonObject.get("item");
			 
			 System.out.println(jsonString);
			 
			 
			 // write the district.txt
			 File file = new File("F:\\Semesters\\Spring-2016\\Database systems\\final project\\district.txt");
			 File filePop = new File("F:\\Semesters\\Spring-2016\\Database systems\\final project\\district_has_race.txt");
			 
			 
			 FileWriter distWriter = new FileWriter(file, true);
			 
			 // write zipCode
			 String zipCode = urlPartTwo;
			 distWriter.write(zipCode + "\t");
			 // write MalePop
			 String malePop = (String) jsonItemObject.get("MalePop");
			 distWriter.write(malePop + "\t");
			 // write FemalePop
			 String femalePop = (String) jsonItemObject.get("FemalePop");
			 distWriter.write(femalePop + "\t");
			 // write AverageHouseValue
			 String aveHouseValue = (String) jsonItemObject.get("AverageHouseValue");
			 distWriter.write(aveHouseValue + "\t");
			 // write IncomePerHousehold
			 String incomePerHousehold = (String) jsonItemObject.get("IncomePerHousehold");
			 distWriter.write(incomePerHousehold + "\t");
			 //write MedianAge 
			 String medianAge = (String) jsonItemObject.get("MedianAge");
			 distWriter.write(medianAge + "\t");
			 // write AverageFamilySize
			 String averageFamilySize = (String) jsonItemObject.get("AverageFamilySize");
			 distWriter.write(averageFamilySize + "\t");
			 //write AreaLand
			 String areaLand = (String) jsonItemObject.get("AreaLand");
			 distWriter.write(areaLand + "\t");
			 // write AreaWater
			 String areaWater = (String) jsonItemObject.get("AreaWater");
			 distWriter.write(areaWater + System.lineSeparator());
			 
			 distWriter.flush();
			 distWriter.close();
			 
			 // write district_has_race.txt
			 FileWriter popWriter = new FileWriter(filePop, true);
			 
			 //write white
			 http.writePopulation(popWriter, jsonItemObject, zipCode, "White", "WhitePop");
			 //write black
			 http.writePopulation(popWriter, jsonItemObject, zipCode, "Black", "BlackPop");
			 // write Hispanic
			 http.writePopulation(popWriter, jsonItemObject, zipCode, "Hispanic", "HispanicPop");
			 //write Asian
			 http.writePopulation(popWriter, jsonItemObject, zipCode, "Asian", "AsianPop");
			 //write Indian
			 http.writePopulation(popWriter, jsonItemObject, zipCode, "Indian", "IndianPop");
			 // write HawaiianPop
			 http.writePopulation(popWriter, jsonItemObject, zipCode, "Hawaiian", "HawaiianPop");
			 
			 popWriter.flush();
			 popWriter.close();
			 
		 }
		
	}
	
	// http send get request
	private String getStringFromUrl(String url) throws Exception{
		
		URL obj = new URL(url);
		HttpURLConnection con = (HttpURLConnection) obj.openConnection();
		
		//set get request method
		con.setRequestMethod("GET");

		BufferedReader in = new BufferedReader(new InputStreamReader(con.getInputStream()));
		
		String inputLine;
		StringBuffer response = new StringBuffer();
		
		while((inputLine = in.readLine()) != null){
			
			response.append(inputLine);			
		}
		in.close();
		
		return response.toString();
	}
	
	// convert the string to a json object
	private JSONObject getJson(String s) throws JSONException{
		JSONObject json = new JSONObject(s);
		return json;
	}
	
	// function to write population 
	private void writePopulation(FileWriter fw, JSONObject jb, String zip, String race, String getIndex) throws IOException, JSONException{
		fw.write(zip + "\t");
		fw.write(race + "\t");
		String content = (String) jb.get(getIndex);
		fw.write(content + System.lineSeparator());
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

}
