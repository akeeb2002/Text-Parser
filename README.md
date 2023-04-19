# Text-Parser

• Web-Based Parser of literary texts that also reports on the word frequencies of the texts being parsed.

• HTML/CSS were used for the front end of the website.

• Implemented MYSQL database to store Source Name, Source URL, Source Begin and Source End. Then
printed out a table with the word frequency which is also stored in the database.

• PHP is used for back-end to connect with database and queries.
Documentation: https://docs.google.com/document/d/1Ebezkyj9CQ_Sjj970nqE64mI5v34jQxHN5A_KF1HL04/edit?usp=sharing


# klevel1_gptstarter

Installation and Usage:

1. Clone the repository to your local machine.
   `git clone https://github.com/issarush/klevel1_gptstarter.git`
2. Change into the project directory.
3. Initialize a virtual environment.
   `python -m venv [envname]`
4. Activate the virtual environment.
   Mac: `source [envname]/bin/activate.`
   Windows: `[envname]/Scripts/Activate`.
5. Install the project dependencies from the requirements.txt.
   `pip install -r requirements.txt`.
6. Place .docx files inside the Docsfiles folder.
7. Run the Main python file via `python main.py`.
8. You will be asked to provide the OPENAI KEY, followed by a prompt regarding the data in the Knowledge folder.
9. You will receive a response.

Adding or Modifying Documents:

The model is using data from the text file(s) present in the Knowledge directory. So to add or modify the documents that the code uses, you can add or modify text files in the Knowledge directory. Each text file should contain one or more documents, separated by a blank line.

Notes:

The createVectorIndex function uses OpenAI's GPT-3 language model to generate vector representations of the text data in the specified directory. These vectors are then saved to a JSON file that can be loaded into memory and used by the answerMe function to generate responses to user input.

The interact.py script contains the answerMe function, which takes as input the path to the JSON file containing the indexed data. The user is prompted to provide a question or prompt, and the GPT model generates a response based on the indexed data.
