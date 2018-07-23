# Thornbury HS Farewell Page
A message board for the community to send there personal message to our beloved retired principal Peter Egeberg. 
Which was further turned into a physical book with all the submissions. 
This was a project I did earlier in my degree where I made it **quickly as possible**


## My Stack (how I hacked it in 2 days)
The backend is quite a funny story.
I wanted to receive messages instantly, as a soon as I got the permission from my high school, teachers and students were asking "where can I send my message?"
Solution: **Google forms :smile:**
In the mean time I created a simple design created the frontend while trying to figure how to link google forms to the site.

I worked out that all submissions are basically pushed into a sheets (excel) on googles end. So I spun up a simple server, Ran my favourite installed LEMP script, and a way we go!

Well... not quite. I had no idea how to trigger when a new submission was made so I ended up creating a cron job that would check the google sheet document every 5minutes for new submissions using their api. Then dumping it into my own database. A classic hack solution. It worked and I was proud.