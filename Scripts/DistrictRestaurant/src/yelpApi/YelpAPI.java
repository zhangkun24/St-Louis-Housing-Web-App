package yelpApi;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.LinkedList;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;
import org.scribe.builder.ServiceBuilder;
import org.scribe.model.OAuthRequest;
import org.scribe.model.Response;
import org.scribe.model.Token;
import org.scribe.model.Verb;
import org.scribe.oauth.OAuthService;

import com.beust.jcommander.JCommander;
import com.beust.jcommander.Parameter;

/**
 * Code sample for accessing the Yelp API V2.
 * 
 * This program demonstrates the capability of the Yelp API version 2.0 by using the Search API to
 * query for businesses by a search term and location, and the Business API to query additional
 * information about the top result from the search query.
 * 
 * <p>
 * See <a href="http://www.yelp.com/developers/documentation">Yelp Documentation</a> for more info.
 * 
 */
public class YelpAPI {

  private static final String API_HOST = "api.yelp.com";
  private static final String DEFAULT_TERM = "restaurants";
  private static final String DEFAULT_LOCATION = "Saint Louis City, MO";
  private static final int SEARCH_LIMIT = 3;
  private static final String SEARCH_PATH = "/v2/search";
  private static final String BUSINESS_PATH = "/v2/business";
  private static final String CATEGORY = "chinese";

  /*
   * Update OAuth credentials below from the Yelp Developers API site:
   * http://www.yelp.com/developers/getting_started/api_access
   */
  private static final String CONSUMER_KEY = "ylvGmA5UAxFFA311wWEx4Q";
  private static final String CONSUMER_SECRET = "DD9AZ7ytbD0iSsG-mtGFkWqVeCQ";
  private static final String TOKEN = "rZv3Rl5NCKKNznVoHkgDhUiW_3VEW2sf";
  private static final String TOKEN_SECRET = "Yz6JpJiX3HhH0ZDnm8I1tw7B4jE";

  OAuthService service;
  Token accessToken;

  /**
   * Setup the Yelp API OAuth credentials.
   * 
   * @param consumerKey Consumer key
   * @param consumerSecret Consumer secret
   * @param token Token
   * @param tokenSecret Token secret
   */
  public YelpAPI(String consumerKey, String consumerSecret, String token, String tokenSecret) {
    this.service =
        new ServiceBuilder().provider(TwoStepOAuth.class).apiKey(consumerKey)
            .apiSecret(consumerSecret).build();
    this.accessToken = new Token(token, tokenSecret);
  }

  /**
   * Creates and sends a request to the Search API by term and location.
   * <p>
   * See <a href="http://www.yelp.com/developers/documentation/v2/search_api">Yelp Search API V2</a>
   * for more info.
   * 
   * @param term <tt>String</tt> of the search term to be queried
   * @param location <tt>String</tt> of the location
   * @return <tt>String</tt> JSON Response
   */
  public String searchForBusinessesByLocation(String term, String location, String category) {
    OAuthRequest request = createOAuthRequest(SEARCH_PATH);
    request.addQuerystringParameter("term", term);
    request.addQuerystringParameter("location", location);
    //request.addQuerystringParameter("limit", String.valueOf(SEARCH_LIMIT));
    request.addQuerystringParameter("category_filter", category); // category
    request.addQuerystringParameter("sort", String.valueOf(2));
    
    return sendRequestAndGetResponse(request);
  }

  /**
   * Creates and sends a request to the Business API by business ID.
   * <p>
   * See <a href="http://www.yelp.com/developers/documentation/v2/business">Yelp Business API V2</a>
   * for more info.
   * 
   * @param businessID <tt>String</tt> business ID of the requested business
   * @return <tt>String</tt> JSON Response
   */
  public String searchByBusinessId(String businessID) {
    OAuthRequest request = createOAuthRequest(BUSINESS_PATH + "/" + businessID);
    return sendRequestAndGetResponse(request);
  }

  /**
   * Creates and returns an {@link OAuthRequest} based on the API endpoint specified.
   * 
   * @param path API endpoint to be queried
   * @return <tt>OAuthRequest</tt>
   */
  private OAuthRequest createOAuthRequest(String path) {
    OAuthRequest request = new OAuthRequest(Verb.GET, "https://" + API_HOST + path);
    return request;
  }

  /**
   * Sends an {@link OAuthRequest} and returns the {@link Response} body.
   * 
   * @param request {@link OAuthRequest} corresponding to the API request
   * @return <tt>String</tt> body of API response
   */
  private String sendRequestAndGetResponse(OAuthRequest request) {
    System.out.println("Querying " + request.getCompleteUrl() + " ...");
    this.service.signRequest(this.accessToken, request);
    Response response = request.send();
    return response.getBody();
  }

  /**
   * Queries the Search API based on the command line arguments and takes the first result to query
   * the Business API.
   * 
   * @param yelpApi <tt>YelpAPI</tt> service instance
   * @param yelpApiCli <tt>YelpAPICLI</tt> command line arguments
   */
  private static JSONArray queryAPI(YelpAPI yelpApi, YelpAPICLI yelpApiCli, String category) {
    String searchResponseJSON =
        yelpApi.searchForBusinessesByLocation(yelpApiCli.term, yelpApiCli.location, category);

    JSONParser parser = new JSONParser();
    JSONObject response = null;
    try {
      response = (JSONObject) parser.parse(searchResponseJSON);
    } catch (ParseException pe) {
      System.out.println("Error: could not parse JSON response:");
      System.out.println(searchResponseJSON);
      System.exit(1);
    }

    JSONArray businesses = (JSONArray) response.get("businesses");
    return businesses;
  }

  /**
   * Command-line interface for the sample Yelp API runner.
   */
  private static class YelpAPICLI {
    @Parameter(names = {"-q", "--term"}, description = "Search Query Term")
    public String term = DEFAULT_TERM;

    @Parameter(names = {"-l", "--location"}, description = "Location to be Queried")
    public String location = DEFAULT_LOCATION;
  }
  
  /**
   * function to write file
   */
  private static void writeFile(FileWriter fw, String s){
	  
  }

  /**
   * Main entry for sample Yelp API requests.
   * <p>
   * After entering your OAuth credentials, execute <tt><b>run.sh</b></tt> to run this example.
 * @throws IOException 
   */
  public static void main(String[] args) throws IOException {
	  /*
	   * the format the the project needs to store
	   * 
	   * id 	name	location-->address    location--->coordinates-->long	location-->coordinates-->lat
	   * 
	   * 
	   * location-->postal_code		rating    url		Type
	   * 
	   * 
	   */
	  // the categories we are trying to get
	  String[] categories = {"chinese", "italian", "japanese", "mexican", "german", "french", 
			  				"tradamerican", "mideastern", "newamerican",  "indpak"};
	  String[] zipArr = {"63147", "63120", "63115", "63112", "63113", "63107", "63108", "63106", "63110",
	 			"63103", "63101", "63102", "63104", "63139", "63118", "63109", "63116", "63111"};
	  LinkedList zipList = new LinkedList<String>();
	  for(int i = 0; i < zipArr.length; ++i){
		  zipList.add(zipArr[i]);
	  }
	  // set a file that stores the data
	  File file = new File("F:\\Semesters\\Spring-2016\\Database systems\\final project\\restaurant.txt");
	  FileWriter restaurantWriter = new FileWriter(file, true);
	  
	  // set up YelpAPI
	  YelpAPICLI yelpApiCli = new YelpAPICLI();
	  new JCommander(yelpApiCli, args);
	  YelpAPI yelpApi = new YelpAPI(CONSUMER_KEY, CONSUMER_SECRET, TOKEN, TOKEN_SECRET);
	  
	  
	  // get all the data 
	  for(int i = 0; i < categories.length; ++i){
		  // get the bussiness array
		  JSONArray bussinessArr = queryAPI(yelpApi, yelpApiCli, categories[i]);
		  for(int j = 0; j < bussinessArr.size(); ++j){
			  JSONObject current  = (JSONObject)bussinessArr.get(j);
			  JSONObject location = (JSONObject) current.get("location"); 
			  
			  // check if zip in the list
			  if(zipList.contains((String) location.get("postal_code"))){
				  
				  // get the id
				  restaurantWriter.write((String) current.get("id") + "\t");
				  //write name
				  restaurantWriter.write((String) current.get("name") + "\t");
				  // write address
				  JSONArray addressArr = (JSONArray) location.get("display_address");
				  String address = (String) addressArr.get(0) ;
				  restaurantWriter.write(address + "\t");
				  // write longitude
				  JSONObject coordinate = (JSONObject) location.get("coordinate");
				  restaurantWriter.write(coordinate.get("longitude") + "\t");
				  // write latitude
				  restaurantWriter.write(coordinate.get("latitude") + "\t");
				  // write postal code
				  restaurantWriter.write((String)location.get("postal_code") + "\t");
				  //write rating
				  restaurantWriter.write( current.get("rating") + "\t");
				  // write url 
				  restaurantWriter.write((String) current.get("url") + "\t");
				  // write category
				  restaurantWriter.write(categories[i] + System.lineSeparator());
				  
			  }
			  
			  
		  }
		  
	  }

    restaurantWriter.flush();
    restaurantWriter.close();
    
  }
}
