/*********************************************************
 * Programme Serrure Codée (partie Arduino)
 * V1.0
 *********************************************************/
int num_lecteur = 0;
#define STX 0x02
#define ETX 0x03
#define DATA_LENGTH 12
#define PART_LENGTH 6

// ================== Brochage ==================
#define RELAY_PIN   41
#define BUTTON_PIN  40

#define LED_GREEN_1 32
#define LED_RED_1   34
#define LED_GREEN_2 46
#define LED_RED_2   48

// ================== Timings ===================
#define BUTTON_TIME       3000

// ================== Structures =================
struct SerialReceiver {
  HardwareSerial &port;
  byte buffer[DATA_LENGTH];
  uint8_t index;
  bool receiving;
};

SerialReceiver receivers[] = {
  { Serial2, {}, 0, false },
  { Serial3, {}, 0, false }
};

// ================== Bouton =====================
unsigned long buttonTimer = 0;
bool relayActive = false;

// =================================================
// ================== SETUP ========================
// =================================================
void setup() {
  Serial.begin(9600);
  Serial1.begin(9600);
  Serial2.begin(9600);
  Serial3.begin(9600);

  pinMode(RELAY_PIN, OUTPUT);
  pinMode(BUTTON_PIN, INPUT_PULLUP);

  pinMode(LED_GREEN_1, OUTPUT);
  pinMode(LED_RED_1, OUTPUT);
  pinMode(LED_GREEN_2, OUTPUT);
  pinMode(LED_RED_2, OUTPUT);

  digitalWrite(RELAY_PIN, LOW);
  allLedsOff();

  Serial.println(F("Systeme pret – version finale"));
}

// =================================================
// ================== LOOP =========================
// =================================================
void loop() {
  handleButton();
  handleSerialInputs();
  handleRaspberryResponse();
}

// =================================================
// ================== FONCTIONS ====================
// =================================================

// ---------- Bouton relais ----------
void handleButton() {
  if (digitalRead(BUTTON_PIN) == HIGH && !relayActive) {
    relayActive = true;
    OuvertureOK(0);
  }

  if (relayActive && millis() - buttonTimer >= BUTTON_TIME) {
    relayActive = false;
    digitalWrite(RELAY_PIN, LOW);
    allLedsOff();
  }
}

// ---------- Réception trames Serial2 / 3 ----------
void handleSerialInputs() {
  for (uint8_t r = 0; r < 2; r++) {
    while (receivers[r].port.available()) {
      byte b = receivers[r].port.read();

      if (b == STX) {
        receivers[r].receiving = true;
        receivers[r].index = 0;
        continue;
      }

      if (!receivers[r].receiving) continue;

      if (b == ETX) {
        if (receivers[r].index == DATA_LENGTH) {

          // 📤 Envoi vers Raspberry Pi
          Serial1.write(&receivers[r].buffer[DATA_LENGTH - PART_LENGTH], PART_LENGTH);
          Serial1.print(',');
          Serial1.println(r);
          num_lecteur = r;
          Serial.print(F("Donnee envoyee depuis Serial"));
          Serial.println(r + 2);
        }
        receivers[r].receiving = false;
      }
      else if (receivers[r].index < DATA_LENGTH) {
        receivers[r].buffer[receivers[r].index++] = b;
      }
      else {
        receivers[r].receiving = false;
      }
    }
  }
}

// ---------- Réponse Raspberry Pi ----------
void handleRaspberryResponse() {
  static String rxLine = "";

  //if (!waitingRaspberry) return;

  while (Serial1.available()) {
    char c = Serial1.read();

    if (c == '\n') {
      rxLine.trim();

      if (rxLine == "OK") {
        OuvertureOK(num_lecteur);        // 🟢 Autorisé
      }
      else if (rxLine == "KO") {
        NonAutorise(num_lecteur);       // 🔴 Refusé
      }
      if (rxLine == "WEB") {
        OuvertureOK(2);        // 🟢 Autorisé
      }

      rxLine = "";
    }
    else {
      rxLine += c;
    }
  }
}


// ---------- LEDs utilitaires ----------
void OuvertureOK(int num_lecteur) {
  allLedsOff();
  if (num_lecteur==1){
  digitalWrite(LED_GREEN_1, HIGH);
  }
  else if (num_lecteur==2){
  digitalWrite(LED_GREEN_1, HIGH);
  digitalWrite(LED_GREEN_2, HIGH);

  }
  else {
  digitalWrite(LED_GREEN_2, HIGH);
  }
  digitalWrite(RELAY_PIN, HIGH);
  delay(2000);
  digitalWrite(LED_GREEN_1, LOW);
  digitalWrite(LED_GREEN_2, LOW);
  digitalWrite(RELAY_PIN, LOW);
}

void NonAutorise(int num_lecteur) {
  allLedsOff();
  if (num_lecteur==1){
    for (int i = 0; i <= 4; i++) {
      digitalWrite(LED_RED_1, HIGH);
      delay(250);
      digitalWrite(LED_RED_1, LOW);
      delay(250);
    }
  }
  else {
    for (int i = 0; i <= 4; i++) {
      digitalWrite(LED_RED_2, HIGH);
      delay(250);
      digitalWrite(LED_RED_2, LOW);
      delay(250);
    }
  }
  digitalWrite(LED_GREEN_1, LOW);
  digitalWrite(LED_GREEN_2, LOW);
}

void allLedsOff() {
  digitalWrite(LED_GREEN_1, LOW);
  digitalWrite(LED_GREEN_2, LOW);
  digitalWrite(LED_RED_1, LOW);
  digitalWrite(LED_RED_2, LOW);
}
