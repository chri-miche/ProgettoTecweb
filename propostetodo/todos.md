#TODO
- CONTENUTO GENERALE migliorare la navigazione
- LAYOUT unificare input ricerca e inserimento messaggio
- LAYOUT ADMIN sistemare visualizzazione
- LAYOUT link e bottoni diversi! (ok discorso rottura convenzioni esterne ma meglio non troppo) link = mat-button ; button = mat-raised-button
- LAYOUT icone foto resize carino
- NEW POST togliere persistent mettere dao

#TODO CONFIGURAZIONE (da fare alla fine)
- PERMESSI FILE PHP e directory (non deve essere possibile navigare tra le directory https://stackoverflow.com/questions/409496/prevent-direct-access-to-a-php-include-file)
- CONFIG INI con info sulla connessione e sul db

#AGGIUNTE (necessarie)
- gestione degli errori e 404
- controlli addizionali sulle form (javascript)

#AGGIUNTE (opzionali)
- feed amici
- ricerca utente
- admin rimuovere post
- admin rimuovere utente
- citazioni uccelli su post
- refactoring file

#DONE
- ADMIN js
- ADMIN inserire immagini uccelli
- LAYOUT UTENTE sistemare visualizzazione
- CONTENUTO ADMIN bottone navigazione indietro
- LOGIN Cambiare Nome utente in Indirizzo email
- NEWPOST strip tags
- COMMENTI strip tags
- MENU visualizzazione come elenco puntato, i bottoni dello stesso colore del bg e separati da una linea chiara
- MENU ADMIN width
- MENU link su se stesso
- CONTENUTO FOOTER scrivere testo non lorem ipsum

#ABORTED (motivo)
- SIDEBAR Menu non blocca azioni nella pagina principale, sistemare (da fare con js)
- PERSISTENT (ADMIN) 0 = null (in favore dell'utilizzo di dao e vo)
- ADMIN commenti sulle colonne del db (in favore dell'utilizzo di dao e vo)
- CONTENUTO ADMIN se la tabella è vuota mettere such empty (tanto live non è mai il caso)

#GIRO TEST FUNZIONALITÀ
##PUBBLICHE
- feed
- catalogo
- ricerca
- visualizza post
- crea utente
- visualizza specie

##PRIVATE
- accedi
- crea post
- like / dislike
- commenta
- befriend

##ADMIN
- inserimento
- modifica
- rimozione