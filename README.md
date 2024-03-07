## What is this...?
This is an example project exploring prompt engineering with the OpenAI API. We will use it to create a simple application that explores the last 50 or so years of history. 

## The Goal
1. We'll use the API to acquire historical data we want. This will require some careful prompt engineering.
2. Using this data, we'll create a rich, static page and save this page to our local filesystem. 
3. We'll upload this page to some host to deploy our site. 

The idea is that we'll use the API to build static content, with Laravel working as a backend that never needs to be publically accessible. Why don't I just use Next with server-side rendering? 

Well...I wanted something even more simple, and I want very explicit control over what leaks into the front end. Using old fashion blade templates with Laravel isn't the worst option for this. 

## TODO
`This project is still under construction...give me a few more days and there will be more here` :D 