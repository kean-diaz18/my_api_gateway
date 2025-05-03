Setup Instructions

Install XAMPP if you haven't already.
Copy the entire my_api_gateway/ folder into your htdocs/ directory (e.g., C:\xampp\htdocs\my_api_gateway).
Start Apache and MySQL from the XAMPP Control Panel.
Create a MySQL database use the apigatewaydb.sql Copy and Paste it.
Valid API Keys Implemented image

How to test features (POSTMAN/ CURL) Remember: You must always include the header X-API-Key when making requests.

Accessing /users Service. •Method: GET •URL: http://localhost/my_api_gateway/api/users •Headers: •X-API-Key: key123 •X-API-Key: key456 ![{CB2288C1-AC47-45E0-87BD-0804616D1015}](https://github.com/user-attachments/assets/420b9de8-5103-408a-8b58-224e4e256884)


Accessing /products Service. •Method: GET •URL: http://localhost/my_api_gateway/api/products •Headers: •X-API-Key: key123 •X-API-Key: key456 ![{EA08F28C-7C28-4B0D-8356-A0A0FBE36B7F}](https://github.com/user-attachments/assets/196189c3-feed-40f4-a0f0-90ab4e58dd98)


Accessing /dashboard Service. •Method: GET •URL: http://localhost/my_api_gateway/api/dashboard •Headers: •X-API-Key: key123 •X-API-Key: key456 ![{8B1E6DD0-A28D-42E5-87C9-2BBACCA36119}](https://github.com/user-attachments/assets/2c2355a9-2a2c-4eab-8645-97391e9ccfdc)



Challenges and Assumptions
Challenge: Implementing dynamic rate limiting has proven difficult due to database constraints and performance considerations.
Assumption: All API keys stored in the database are considered valid unless explicitly removed.  

Unauthorized User:![{10495A27-F280-4C1D-98D7-4EFE38B78E36}](https://github.com/user-attachments/assets/8ad9fa34-11a1-4103-bb66-8c4738c956be)



Rate Limiter: ![{851D2A8C-16B6-44DB-9CCC-5A7CB101496D}](https://github.com/user-attachments/assets/fd1097e4-6642-486c-b2bd-2b26dbbd9753)



Gateway Logs: ![{013095A5-32F3-49E5-8639-0B53D9680493}](https://github.com/user-attachments/assets/79835bc5-e430-4cd6-ad7d-e4eb732d1f17)



