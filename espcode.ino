#include <dummy.h>
#include <DHT.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>


#define CW 4
#define DHTPIN 5
#define DHTTYPE DHT11   // DHT 11 
DHT dht = DHT(DHTPIN, DHTTYPE);
float Celsius = 0;

float h ,t,hic;

const char *ssid = "WiFi-NoviDom";
const char *password = "";

float val;
int potpin = A0;
int i =0;

void setup() {
  // put your setup code here, to run once:
  Serial.begin(115200);
  connectToWiFi();
  pinMode(LED_BUILTIN,OUTPUT);
  pinMode(CW, OUTPUT);
  dht.begin();
}

void loop() {
  // put your main code here, to run repeatedly:
  
  delay(10000);
  
  h = dht.readHumidity();
  t = dht.readTemperature();
  hic = dht.computeHeatIndex(t, h, false);
  Serial.println(h);
  Serial.println(t);
  Serial.println(hic);
  val = analogRead(potpin);            
  val = map(val, 550, 0, 0, 100)+100;


  if (val>=40 ){
    Serial.println("Slavina je zatvorena.");
    delay(500);
    digitalWrite(CW,LOW);
  }else{
    digitalWrite(CW,HIGH); //Motor runs clockwise// 
    delay(500);
    Serial.println("Slavina je otvorena.");
  }
  if (i>= 30){
    postData();
    i=0;
  }else {
    i += 1;
  }
 
}
void connectToWiFi() {
//Connect to WiFi Network
   Serial.println();
   Serial.println();
   Serial.print("Connecting to WiFi");
   Serial.println("...");
   WiFi.begin(ssid, password);
   int retries = 0;
while (WiFi.status() != WL_CONNECTED) {
   retries++;
   digitalWrite(LED_BUILTIN, HIGH);
   delay(500);
   Serial.print(".");
   digitalWrite(LED_BUILTIN, LOW);
}


if (WiFi.status() == WL_CONNECTED) {
    Serial.println(F("WiFi connected!"));
    Serial.println("IP address: ");
    Serial.println(WiFi.localIP());
}
    Serial.println(F("Setup ready"));
}


void postData() {

   WiFiClient client;
   HTTPClient http;
   const int httpGetPort = 80;
   String serverName = "http://34.116.228.180/writejson.php?h=" + String(h,1) + "&t=" + String(t,1) + "&hic=" + String(hic,1) + "&val=" + String(val,1);
   http.begin(client, serverName);

   

   
  

   String httpRequestData = "";
   Serial.print("httpRequestData: ");
   Serial.println(httpRequestData);

   int httpResponseCode = http.GET();

   if (httpResponseCode>0) {
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
    }
    else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }
    // Free resources
    http.end();
       
    

      //end client connection if else
                        
          
      client.stop();
      delay(10000);

}
