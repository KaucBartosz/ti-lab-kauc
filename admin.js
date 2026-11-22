const formDodaj = document.getElementById('form-dodaj');

formDodaj?.addEventListener('submit', async function (e) {
  e.preventDefault();
  const nazwa = document.getElementById('nazwa').value.trim();
  const cena = parseInt(document.getElementById('cena').value, 10);

  const odpowiedz = await fetch('api.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ nazwa, cena }),
  });

  if (odpowiedz.status === 201) {
    const usluga = await odpowiedz.json();
    const tbody = document.querySelector('table tbody');
    const tr = document.createElement('tr');
    const tdNazwa = document.createElement('td');
    tdNazwa.textContent = usluga.nazwa;
    const tdCena = document.createElement('td');
    tdCena.textContent = usluga.cena + ' zł';
    const tdAkcja = document.createElement('td');
    tr.append(tdNazwa, tdCena, tdAkcja);
    tbody.append(tr);
    formDodaj.reset();
  } else if (odpowiedz.status === 401) {
    alert('Zaloguj się ponownie.');
  } else {
    let komunikat = 'Błąd dodawania.';
    try { komunikat = (await odpowiedz.json()).blad || komunikat; } catch (e) {}
    alert(komunikat);
  }
});
