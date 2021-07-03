# Taylor Otwell: 14 Answers on Youtube - Jetstream, Laravel UI, his "yes-men" etc.

Last night Taylor Otwell went on Youtube Live with an hour-long video called Jetstream Discussion to, in his own words, "to get some things in the open that, I think, are misunderstandings". I decided to transcribe/summarize those answers into a readable Q&A - for those who don't want to spend an hour watching the video.

Also, I think Taylor needs to be heard, especially since he's not on Reddit anymore.

In total, 10 questions/topics on technical stuff, and 4 questions/topics about personal opinions and the community.

Notice: This article is not my opinion, it's just word-for-word what Taylor said. Opinions are welcomed in the comments, but please read the full thing, or, even better, watch the full video, to feel the context on Taylor's emotions when speaking.

## Section 1. Technical questions.

## Question 1. Is Laravel/UI going away?

Actually, internally, in Laravel company Slack, I already discussed that we would be maintaining that project indefinitely. We have to keep it working with Laravel 8, 9, 10, ... Just out of service for people who are already using it, which is thousands of people.

That was always the plan. But I guess the wording on the Readme where it said "it's not recommended to be used on new projects". That is actually still my stand that I don't personally recommend it, but it gave people impression that it's going away some time in the future.

We fixed that and said that it's just a simple scaffolding, and if you need something more robust, you should use Jetstream.

Also, in Laravel UI, the way Vue scaffolding is configured in Laravel UI, is already a stack that no one uses in production. Vue Router is not set up, there's no good story for the data hydration.

So, at this point, Laravel UI is gonna stay as it is, no plans to add more presets, convert to Tailwind or any of that stuff.

## Question 2. Bootstrap vs Tailwind

I actually find Bootstrap way harder to customize than Tailwind, at this point. We just had this problem in Envoyer the other day, doing something with Bootstrap in Envoyer was super painful.

Another irony of the whole thing is people are saying that Jetstream is too opinionated, but Tailwind is much less opinionated than Bootstrap.

But people are working on different things. For some people, that simple Bootstrap scaffolding is really helpful. And I can actually sympathize with that, just a couple of years ago I could only do Bootstrap, I could never do Tailwind at all.

## Question 3. What if someone builds something INTO Jetstream? Like simple Blade?

Simple Blade is not what I would use or build myself. That's the beauty of it, that you can build it, with Laravel Fortify. You can build it and release it. That is open-source.

If you build it INTO Jetstream, then I would have to maintain it. This is again, a big misconception: just because someone builds it, doesn't mean that I'm necessarily gonna merge it, because if I merge it, now I'm responsible for it. Now I take maintenance burden. We've had things in the past where someone would build something like that, contributed to the framework and told "I'll stick around to maintain it". It's no knock on them, but after a couple of years, sometimes they are not doing PHP at all anymore, they moved on to a differrent job, they write Python, Ruby or whatever else, so they're not around to maintain it, so it's all on me.

## Question 4. About Livewire and Inertia

I think there's a confusion about what Livewire and Inertia are. Livewire doesn't turn your whole application into Livewire application. Certain parts of your app can use Livewire, and the rest of it is just PHP and Blade views. So you can use Livewire on one form of the application, or a couple of forms.

And Jetstream uses it for some of the forms, like two-factor stuff, updating profile information, but that doesn't mean that you have to use it throughout your application. We just use it in those spots because it just makes the UX a lot smoother, it feels like a JavaScript app.

For example, the login forms on Jetstream don't use Livewire, because it's not necessary on those forms.

On the Inertia side, I think people are making it bigger than it is. It's a pretty small library, in terms of what it does, all it really does is replace the Vue Router side of Vue so you can use server-side routing with Laravel Router, and then it replaces how you get data into your views. But that's really it. The rest of it is just writing Vue and Vue components.

I was actually very close to doing a Vue Router in Jetstream, instead of Inertia. But I just felt Inertia was so much better to work with, in a modern Laravel app. I still think non-Inertia Vue way would be nice, but it would be just too many front-ends to maintain.

I like both Livewire and Inertia. While building Jetstream, I was so torn which one to choose, that I just decided to include them both. It felt pretty risky, because I had to take on to maintain two sets of front-end, but I felt those are both great projects, made by Laravel community members.

## Question 5. Jetstream has too much in it, compared to Laravel/UI

I may change what is enabled by default (and he did), I sort of agree that probably profile photo stuff and API stuff can be disabled out-of-the-box.

## Question 6. Plain Blade stack for Jetstream would be pretty simple to make?

It's not really simple as you would think. Mainly, because some of those interactions are just really nice with Livewire, like two-factor authentication, where we need to enable it, confirm the password, regenerate the recovery codes. And when you start turning it into just plain vanilla Blade and Bootstrap, and doing full page reloads with those interactions, it feels really junkie, it doesn't feel like a smooth user experience.

## Question 7. Fortify's role in all of this?

So here's the goal that always was there: for me, Fortify is sort of the interface, and Jetstream is the implementation. I knew not everyone would want to use Jetstream, that's why there are two separate packages, that's why Fortify exists, a headless authentication back-end, that you can build any front-end on top of. So if you want to build React front-end scaffolding for Laravel, you can use Fortify to power the back-end of that.

## Question 8. Feels Jetstream has a lot of files, like button, modal, secondary button...

Glad someone mentioned it. That's driven by Tailwind. When you're first making something with Tailwind and see this button element has fifteen classes on it, and the solution to that is to componentize all of those things.

That's why you have those things as a button blade component, or danger button and stuff like that. In this way, you don't have to repeat any of those Tailwind classes, it's sort of a DRY principle, we have a common button pattern and extract it into a Blade component, and then we don't have to write custom CSS at all. So in Bootstrap you would do class="btn btn-primary" and there's not much to really extract there, but with Tailwind it's a recommended approach to componentize things, that's why Jetstream has more files.

## Question 9. What are Jetstream Actions and Why?

Jetstream has two different front-ends. So one of the problems I had to solve was how do I not duplicate the back-end logic for both front-ends. And that's why Jetstream has this Actions, so I wouldn't have to write two different back-end as well.

Are they a Command Bus pattern? Not in a true Command Bus sense, I don't know what that pattern is, like a Service Class. To me, Action classes are just named functions, just one method that does particular thing, they are a good pattern in certain situations, where you want to encapsulate one little process and give it a name.

## Question 10. What's wrong with the old Auth Controller-based scaffolding?

I was never really a huge fan of customizing authentication by overriding various protected methods, so, in Fortify I tried to move a lot of that into configuration values. Example of that is where to redirect people after they login, and in old Auth that would be a property, or override method. Same with customizing logic, in the docs of Fortify we use Closures, but of course you can use invokable classes, and link that in Fortify Service Provider. Or use Events, like listen to Illuminate Auth Registered event, instead of overriding methods.
Section 2/2. Personal opinions and emotions.

## Question 11. Taylor is not listening to the community?

From my perspective, I WAS listening to the community. A pretty big lesson learned: Twitter is really hard to cipher what people actually want. Leading up to Laravel 8, there were tons of people tweeting at me: we want Tailwind scaffolding, we want TALL stack, we really like Inertia etc. And that was all I heard.

Nobody said "We want more Bootstrap". It was all about Tailwind, Alpine, Inertia, Livewire. I really like Livewire and Inertia myself, I think they are the most productive ways to do front-end in Laravel.

There was a lot of talk about "community this" and "community that", but everyone wants to define their view as the community view, and that's not accurate. If you love Laravel Jetstream, you want to define the community as people who love Laravel Jetstream, and everyone else is complaining. If you love Laravel UI, you view the community as Laravel UI is what the community actually wants. Both of those views are really stupid, honestly. There's no homogenous Laravel community, it's used by hundreds of thousands of people.

For example, this whole Jetstream "uproar", you're talking about maybe 100 people involved, that's 0.1% of Laravel Twitter followers. So there is no homogenous Laravel community to say "this is what the community wants". So I just build what I think is best.

Another point - to involve community with surveys. The whole premise is wrong. So we would do a survey to tell people what to work on in their free time? It's wrong. I work on what I'm passionate about. I don't do a survey to tell me what to spend my open-source time on. To me, it just doesn't mae sense. [more on commercial stuff later]

## Question 12. Taylor has "echo-chamber" of "yes-men"?

It's been a little frustrating to see on Reddit people saying I have that "echo-chamber", a group of "yes-men" to encourage me in my ideas, and nothing could be further from the truth.

I didn't tell anyone about Jetstream, actually, not even Laravel employees, until it was really far along in development. It was just my idea. Jetstream was the answer to what would I use to build a Laravel app productively in 2020.

My friends, people who I'm friends with in Laravel, we don't really talk about Laravel, at all. With Adam Wathan we just play Rocket League. When people are tweeting at me, that's not me going out and seeking people to confirm my ideas.

About opinions. I'm not really bothered by people having opinions, I'm bothered by misinformation about how I work on things and why I work on things. I don't work on things because I live in an echo-chamber, that's not why I work on things.

## Question 13. Important distinction between open-source and premium stuff?

And all of the open-source stuff that I build is just what I want to build, for my own personal fulfilment, something I'm passionate about.

I also happen to own businesses that were inspired by open-source products. When I build commercial stuff, I build for our customers.

For example, I won't maintain Jetstream on Bootstrap, because I don't use Bootstrap. I would never maintain something I would never use.

## Question 14. About constructive criticism

I think when people provide criticism, it's important to be really specific. It's been a little frustrating, some people they don't like Jetstream or they think it's hard, but they don't give any specific thing that is actually hard. A specific thing that I could go into the code and actually improve right now.

For example, generic statement, "Jetstream feels bloated". There's no actionable takeaway from that, I can't go to the code and run some "de-bloater" or something, I need something more concrete.

And I actually got quite a lot of that, on the first week of Jetstream release.

I remember Andrey Butov tweeted he didn't like Jetstream. I knew Andrey from years ago, so I just emailed him questioning what problems he had, so I could fix them. And he sent me back a nice email with bullet-pointed list, 4-5 things he didn't like, and they were all really valid, and I've put them into Jetstream the first week after release.

Another community member Steven made some posts on Twitter, so I DM'ed him saying "I'm gonna be working on Jetstream all week, can you tell me exactly what you didn't like about it". And he actually made a Github issue that was really detailed, and we were able to improve a lot of stuff. All customization section in Jetstream docs is totally inspired by Steven's criticisms.

So, if you have a problem with Jetstream, please share it as specifically as possible, particular classes or files. A lot of people say "I can't customize it". Well, what exactly are you customizing, what are you trying to do, what is your end goal? That is the key information.

