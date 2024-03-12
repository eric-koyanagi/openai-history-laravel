## What is this...?
This is an example project exploring prompt engineering with the OpenAI API. We will use it to create a simple application that explores the last 50 or so years of history. 

## The Goal
1. We'll use the API to acquire historical data we want. This will require some careful prompt engineering.
2. Using this data, we'll create a rich, static page and save this page to our local filesystem. 
3. We'll upload this page to some host to deploy our site. 

The idea is that we'll use the API to build static content, with Laravel working as a backend that never needs to be publically accessible. Why don't I just use Next with server-side rendering? 

Well...I wanted something even more simple, and I want very explicit control over what leaks into the front end. Using old fashion blade templates with Laravel isn't the worst option for this. 

## Running the App

1. First, seed the schema (TODO) with `php artisan migrate`. Populate your first SystemRole into the database manually. 
2. Install your API keys into .env (see `.env.example`). This also sets the date ranges you want to generate history for! 
3. Use the CLI command `php artisan app:get-histories {roleId}` to obtain the first batch of data from OpenAI. This may take several minutes! You may need to try multiple times, especially if on the free plan where limits are strict.
5. Go to http://localhost:8000/build-page (or use whatever port you're running the local site on) to create a static page based on this data. You can really just save this page and upload it somewhere else!
6. The key with generative AI is iteration. You may need to tune your prompt and try again. Go back to step 3 and the system will repull if you provide a new roleId. The page builder always uses the freshes data to build a page.

## The Structure
We have a few structures to support our app: 

- The *History* model stores the output from GPT in a simple, structured way. Histories belong to a DataRun.  
- The *DataRun* model tracks each "crawl" of data pulled from GPT. This allows us to have very simple persistence in case a crawl is interrupted. Each DataRun has many histories.
- The *SystemRole* model remembers the exact system role we use to extract data from the API. This allows us to track exactly what prompt is associated with our data.

The idea is that we don't just want to import a bunch of data from OpenAI and save it to the database. Because we know in advance that we may need several iterations to tune the prompt, we want this to be trackable so that we can understand the differences in content between prompts. We could even generate multiple pages side-by-side to see these changes if we want. 

Also, this allows us to have many versions of the same data with different prompts *and* different models. 

## Expansion
We could create a API interface and code our OpenAPI service against that. Then, we could implement more than just OpenAPI and test how Gemini et. al. handle the same prompts. In my opinion, because there's so much potential trial and error, we need structures like this to tune the content.

Another simple TODO is to create a WYSIWYG around the SystemRole object. I didn't do this because I am happy enough working in SQL.

## TODO
`This project is still under construction...give me a few more days and there will be more here` :D 