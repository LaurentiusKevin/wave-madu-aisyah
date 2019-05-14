import requests
import mysql.connector
from bs4 import BeautifulSoup
from requests_html import HTMLSession

def scrapeNinja(resi):
    session = HTMLSession()
    resi = 'NVIDMDAIS190420014'
    url = session.get('https://berdu.id/cek-resi?courier=ninja&code='+resi+'&secret=RUzROx')
    url.html.render()
    soup = BeautifulSoup(url.html.html, "lxml")
    container = soup.find('div', class_='cekresi_table view div').find_all('div')
    # container = soup.find('body')
    totalRow = len(container) - 1
    return container[totalRow].text

mydb = mysql.connector.connect(
    host="localhost",
    user="admin",
    passwd="admin",
    database="reseller_madu"
)

mycursor = mydb.cursor()
mycursor.execute("SELECT no_transaksi,no_resi FROM kasir_mst where no_resi!=''")
myresult = mycursor.fetchall()

for x in myresult:
    print(x)
    # resi = str(sys.argv[1])
    noTransaksi = x[0]
    resi = x[1]
    status = scrapeNinja(resi)
    
    sql = "DELETE FROM pengiriman_onhold WHERE no_transaksi='"+noTransaksi+"'"
    mycursor.execute(sql)
    mydb.commit()
    
    sql = "INSERT INTO pengiriman_onhold (no_transaksi, status_pengiriman, keterangan, follow_up) VALUES (%s, %s, %s, %s)"
    val = (noTransaksi, status, 'berhasil', '')
    mycursor.execute(sql, val)
    mydb.commit()
    print(mycursor.rowcount, "record inserted.")