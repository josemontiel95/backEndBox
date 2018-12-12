MySQL [lacocspuedic]> SELECT claveEspecimen, id_registrosCampo FROM registrosCampo WHERE  formatoCampo_id=1169;
+-----------------------+-------------------+
| claveEspecimen        | id_registrosCampo |
+-----------------------+-------------------+
| PVF-XI-15-GLCC-02-366 |              1485 |
| PVF-XI-15-GLCC-06-367 |              1486 |
| PVF-XI-15-GLCC-09-368 |              1487 |
| PVF-XI-15-GLCC-10-369 |              1488 |
| PVF-XI-15-GLCC-11-370 |              1489 |
| PVF-XI-15-GLCC-12-371 |              1490 |
| PVF-XI-15-GLCC-16-372 |              1491 |
| PVF-XI-15-GLCC-18-373 |              1492 |
| PVF-XI-15-GLCC-19-374 |              1493 |
+-----------------------+-------------------+
9 rows in set (0.10 sec)



UPDATE registrosCampo SET claveEspecimen = "PVF-XI-8-GLCC-02-366" WHERE id_registrosCampo=1485;
UPDATE registrosCampo SET claveEspecimen = "PVF-XI-8-GLCC-06-367" WHERE id_registrosCampo=1486;
UPDATE registrosCampo SET claveEspecimen = "PVF-XI-8-GLCC-09-368" WHERE id_registrosCampo=1487;
UPDATE registrosCampo SET claveEspecimen = "PVF-XI-8-GLCC-10-369" WHERE id_registrosCampo=1488;
UPDATE registrosCampo SET claveEspecimen = "PVF-XI-8-GLCC-11-370" WHERE id_registrosCampo=1489;
UPDATE registrosCampo SET claveEspecimen = "PVF-XI-8-GLCC-12-371" WHERE id_registrosCampo=1490;
UPDATE registrosCampo SET claveEspecimen = "PVF-XI-8-GLCC-16-372" WHERE id_registrosCampo=1491;
UPDATE registrosCampo SET claveEspecimen = "PVF-XI-8-GLCC-18-373" WHERE id_registrosCampo=1492;
UPDATE registrosCampo SET claveEspecimen = "PVF-XI-8-GLCC-19-374" WHERE id_registrosCampo=1493;