# Override per pagina — il meta box (H32, G12)

> Schermata di modifica di pagina/articolo, colonna laterale:
> **Arena: opzioni pagina**. Diagramma: [diagrammi/meta-box.svg](diagrammi/meta-box.svg)

## Cosa controlla

| Opzione | Effetto |
| --- | --- |
| Nascondi titolo | La pagina parte dal contenuto (hero pieno). |
| Header trasparente | L'header si fonde con l'eroe e si opacizza allo scroll. |
| Nascondi footer | Pagine legali, landing, ringraziamenti. |
| Nascondi sidebar | Layout a piena larghezza per quella sola pagina. |
| Contenitore + larghezza | Override di boxed/fullwidth/narrow e dei rem. |
| Preset tipografico | Un'altra voce tipografica solo per questa pagina. |
| Variante header | standard / transparent / sticky. |

## Salvataggio e reset

- **Salva override**: passa dalla REST API `arena/v1/meta/<id>`, è tracciato
  nel Journal (`meta.save`) con i valori precedenti e annullabile.
- **Reset totale**: un click azzera **tutte** le chiavi Arena della pagina
  (anche il reset è tracciato).

Le chiavi sono meta standard (`_arena_*`) esposte in REST: nessun campo
proprietario, leggibili da qualsiasi integrazione.

## Nota sui template

Gli override sono letti dal tema al momento del rendering (classi body e
attributi), non duplicati nei template: cambiare tema figlio non li perde.
