#define IIC_ADDR  uint8_t(0x76)
#define BME_SCK 13
#define BME_MISO 12
#define BME_MOSI 11
#define BME_CS 10

#include "seeed_bme680.h"
#include <ChainableLED.h>   
#include <WiFi.h>
#include <HTTPClient.h>
#include <ESPAsyncWebServer.h>

ChainableLED leds(14, 32 , 1); //pin utilisées par la led
AsyncWebServer server(80); // port du server

Seeed_BME680 bme680(IIC_ADDR);

const char *ssid = "LilianS21+";
const char *password = "lilian38800";

const int capteurLumierePin = A2; // Capteur de lumiere

void notFound(AsyncWebServerRequest *request)
{
  request->send(404, "text/plain","Not found");
}



void setup() {
    Serial.begin(9600);
    while (!Serial);
    Serial.println("Serial start!!!");

    while (!bme680.init()) {
        Serial.println("bme680 init failed ! can't find device!");
        delay(10000);
    }
  WiFi.begin(ssid, password);
  
  Serial.print("Tentative de connexion au réseau ");
  Serial.println(ssid);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  Serial.println("Connexion établie !");
  Serial.print("Adresse IP de l'appareil : ");
  Serial.println(WiFi.localIP());


server.on("/eteindreAlarme", HTTP_GET, [](AsyncWebServerRequest *request) {
    Serial.println("alarme éteinte");

    request->send(200, "text/plain", "LED éteinte");

  leds.setColorHSB(0, 0, 0, 0);  // Éteind la LED

});

server.on("/allumerAlarme", HTTP_GET, [](AsyncWebServerRequest *request) {
    Serial.println("alarme allumée");

    request->send(200, "text/plain", "LED éteinte");
    leds.setColorHSB(0, 0, 0.5, 0.5);  // LED en rouge avec une luminosité de 0.5

});

server.onNotFound([](AsyncWebServerRequest *request){
    Serial.println("serveur non trouvé");
});
  server.begin();

}

void loop() {
    if (bme680.read_sensor_data()) {
        Serial.println("Failed to perform reading :(");
        return;
    }
    Serial.print("temperature ===>> ");
    Serial.print(bme680.sensor_result_value.temperature);
    Serial.println(" C");

    Serial.print("pressure ===>> ");
    Serial.print(bme680.sensor_result_value.pressure / 1000.0);
    Serial.println(" KPa");

    Serial.print("humidity ===>> ");
    Serial.print(bme680.sensor_result_value.humidity);
    Serial.println(" %");

    Serial.print("gas ===>> ");
    Serial.print(bme680.sensor_result_value.gas / 1000.0);
    Serial.println(" Kohms");

    int valeurLue = analogRead(capteurLumierePin); // Lecture de la valeur de la lumiere
    float puissanceLumineuse = map(valeurLue, 0, 1023, 0, 100); // Converti la valeur lue en une valeur de puissance lumineuse entre 0 et 100
    Serial.print("Puissance lumineuse : ");
    Serial.println(puissanceLumineuse);
    envoyerDonnees(bme680.sensor_result_value.temperature, bme680.sensor_result_value.humidity, (bme680.sensor_result_value.pressure / 1000.0), puissanceLumineuse);
    Serial.println();
    Serial.println();

    delay(150000);
}

// URL de Symfony
const char *serverUrl = "https://192.168.37.31:8000/mesures/ajout";

void envoyerDonnees(float temperature, float humidite, float pression, float luminosite) {
    HTTPClient http;
    
    // Prépare les données a envoyer en JSON
    String data = "{\"temperature_c\":" + String(temperature) + ",";
    data += "\"humidite\":" + String(humidite) + ",";
    data += "\"pression\":" + String(pression) + ",";
    data += "\"luminosite\":" + String(luminosite) + "}";
    
    // Requête POST vers le serveur Symfony
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/json");
    int httpCode = http.POST(data);
    Serial.println(data);
    
    // Vérifie le code de réponse HTTP
    if (httpCode == HTTP_CODE_OK) {
        Serial.println("Données envoyées avec succès !");
    } else {
        Serial.println("Échec de l'envoi des données !");
    }
    Serial.println(httpCode);
    http.end();
}