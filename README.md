# Playground Sessions Backend Code Exercise (Lumen)

## Scenario
Take this hypothetical situation.

We are making an iOS and Android app for teachers.
A teacher will select a student to see all lessons, and whether that student completed each lesson.

Each app will get its data from the JSON REST API endpoint:

```
/student-progress/{userId}
```

Where `{userId}` is the user id of the student.

You inherit this codebase.

You remember how the data is structured:
- Lessons contain several segments.
- A user can create practice records for a segment.

You look over the codebase and realize that several problems exist in this endpoint.
1. Business rules (eg. whether a user has completed a lesson) would be duplicated by each app.
1. It is too slow, even with a reasonable amount of practice records.

Luckily, both front-end developers agree that the endpoint needs to change before it is used.
You all agree to the following data structure for the response:

```
{
  "lessons": [
    {
      "id": 32,
      "difficulty": "Rookie",
      "isComplete": true
    }
  ]
}
```

## Instructions

Solve all problems with this codebase.
- Create a new data structure for the response.
- Codify the following business rules.
  - A lesson is complete if each segment has at least one practice record with a score of 80% or more.
  - Difficulty categories ("Rookie", "Intermediate", "Advanced") are associated with difficulty numbers
    [1,2,3], [4,5,6], [7,8,9], respectively.
- Ensure the response time for user id 1 is under 500ms for the given dataset.
  Right now the response time is about 2 seconds.

Code should be cleanly written in self-contained parts, each having one responsibility,
according to the Single Responsibility Principle (SRP).
For example, application logic (eg. extracting query parameters from a URL)
should be separate from business logic (eg. determining whether a lesson is complete).

You have full reign over the codebase. You can add or remove any packages you like. Everything is fair game.

We are testing your ability to organize code cleanly, with SRP, not your knowledge of the Laravel/Lumen frameworks.

Try to commit often and with small changes, so we can see what you are doing.

If you have a particular strength (say documenting APIs), feel free show it off.

You might benefit from knowing that all 3 problems can be solved without use of a cache.

## Deliverables

Email ben@playgroundsessions.com with a link to your git repository.

## Getting Started

Getting started is simple with PHP's built-in web server.
1. Have PHP 8.0 installed.
1. Follow the [Development Environment Setup](readme/built-in-php-server.md)

## Go!

We look forward to seeing your code! 
