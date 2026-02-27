<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>

<body>
    <style>
        .newsletter-feed {
            max-width: 900px;
            margin: 80px auto;
        }

        .newsletter-feed article {
            margin-bottom: 40px;
        }

        .newsletter-feed img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
            object-fit: cover;
        }

        .newsletter-feed h3 {
            margin: 0 0 8px 0;
        }

        .newsletter-feed a {
            text-decoration: none;
            color: black;
        }

        .newsletter-feed small {
            color: #777;
        }
    </style>
    <!-- newsletter form -->
    <section class="newsletter">
        <form id="newsletter-form">
            <input type="email" name="email" placeholder="Enter your email" required />
            <button type="submit">Subscribe</button>
        </form>
        <p id="form-message" class="form-message" style="display:none;">
            <span class="icon"></span>
            <span class="text"></span>
        </p>
    </section>
    <section class="newsletter-feed">
        <h2>Latest from the newsletter</h2>
        <div id="rss-feed"></div>
    </section>
    <script>
        fetch("/rss.php")
            .then(res => res.json())
            .then(posts => {
                const container = document.getElementById("rss-feed");

                posts.forEach(post => {
                    const article = document.createElement("article");

                    article.innerHTML = `
        ${post.image ? `<img src="${post.image}" alt="${post.title}">` : ""}
        <h3><a href="${post.link}" target="_blank">${post.title}</a></h3>
        <small>${post.pubDate}</small>
        <p>${post.description.substring(0, 120)}...</p>
      `;

                    container.appendChild(article);
                });
            });
    </script>
    <script>
        const successIcon = `
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
  <path fill="#5be1a3" fill-rule="evenodd"
    d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18m-.232-5.36l5-6l-1.536-1.28l-4.3 5.159l-2.225-2.226l-1.414 1.414l3 3l.774.774z"
    clip-rule="evenodd"/>
</svg>`;

        const errorIcon = `
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
  <path fill="#ff4d4f"
    d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0-20m3.5 13.5l-1 1L12 13.5l-2.5 2.5l-1-1L11 12L8.5 9.5l1-1L12 10.5l2.5-2.5l1 1L13 12z"/>
</svg>`;

        document.getElementById("newsletter-form").addEventListener("submit", async function (e) {
            e.preventDefault();

            const email = this.email.value;
            const message = document.getElementById("form-message");
            const icon = message.querySelector(".icon");
            const text = message.querySelector(".text");

            try {
                const res = await fetch("/subscribe.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "email=" + encodeURIComponent(email)
                });

                if (res.ok) {
                    icon.innerHTML = successIcon;
                    text.textContent = "You're subscribed.";
                    message.classList.remove("error");
                    message.classList.add("success");
                    message.style.display = "flex";
                    this.reset();
                } else {
                    throw new Error();
                }

            } catch (err) {
                icon.innerHTML = errorIcon;
                text.textContent = "Something went wrong.";
                message.classList.remove("success");
                message.classList.add("error");
                message.style.display = "flex";
            }
        });
    </script>
</body>

</html>