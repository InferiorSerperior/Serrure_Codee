from pymysql import*
from serial import*
db=connect(host="localhost",user="root",password="DylanHugoAlexis",database="serrure")
cur = db.cursor()
ser = Serial("/dev/serial0",9600, timeout=1)
while True:
    line = ser.readline()
    if line:
        try:
            line= line.decode('utf-8').strip()
            print("Texte reçu:", line)
            msg_recu=line.split(",")
            code=msg_recu[0]
            type_entree=msg_recu[1]
            autorisation=cur.execute("SELECT * FROM utilisateurs WHERE code = %s",(code,))
            if autorisation == 0:
                print("Badge incorrect! Entrée non autorisée.")
                ser.write("KO\n".encode())
            else:
                print("Badge reconnu, entrée autorisée. Sauvegarde du passage en cours...")
                cur.execute("INSERT INTO journal (user_id,date_action,actionIO) VALUES (%s,NOW(),%s)",(code,int(type_entree)))
                db.commit()
                print("Passage sauvegardé.")
                ser.write("OK\n".encode())
        except UnicodeDecodeError:
            print("Erreur de communication: impossible de décoder les données reçues!")
            print("Données reçues :",line)