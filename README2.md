
# Instructions to use
1.Add your api key for "https://api.exchangeratesapi.io/v1/latest" to the .env file in the root of the project to the variable "API_KEY".

2.Use "composer install" to install dependencies.

3.Use "php script.php" to run entries in "input.csv".
# Alternative usage:
1.Add your api key for "https://api.exchangeratesapi.io/v1/latest" to the .env file in the root of the project to the variable "API_KEY".

2.Run "docker build -t my-app . -f docker/Dockerfile"

3.Run "docker run my-app"

# Documentation
The system simulates transaction processing.
The system was designed to be extensible, it could be more generalized for each type of operation, 
but in order to keep the code as flexible as possible I thought it would be better to not overgeneralize.

The part of the code that I think could be improved on:

1.The matching system, each rule has 2 separate functions for matching, this could be reduced and simplified but 
I have limited time.

2.The base currency is currently hard-coded but could be set from the ".env" file, it wouldnt result in many changes.

3.Some verifications are not done, for example the base currency is not verified if its the correct type.

4.The history service is to resolve the requirements, if a database would be used instead, the query for the database 
would be much more simple.
