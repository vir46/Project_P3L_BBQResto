<template>

    <body>
        <div class="container">
            <h2>Hello,</h2>
            <h1>It's Virtus Tan Here</h1>
            <p> @Virtustan</p>
            <p>
                <a href="https://twitter.com/virtus_tan" target="_blank"><img
                        src="https://namratapdrjs.netlify.app/3d-terrain-intro/assets/twitter.svg" alt="twitter" /> </a>
                <a href="https://instagram.com/virtustan" target="_blank"><img
                        src="https://namratapdrjs.netlify.app/3d-terrain-intro/assets/instagram.svg"
                        alt="instagram" /></a>
            </p>
        </div>
    </body>
</template>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap');

    html {
        height: 100%;
    }

    body {
        font-family: 'Roboto Mono', monospace;
        overflow: hidden;
        width: 100%;
        height: 100%;
        text-align: center;
        margin: 0;
        background-color: black;
        color: #a6a6a6;
    }

    a {
        text-decoration: none;
    }

    .container h1 {
        color: white;
        font-size: 190%;
        overflow: hidden;
        /* Ensures the content is not revealed until the animation */
        border-right: .1em solid #a6a6a6;
        /* The typwriter cursor */
        white-space: nowrap;
        /* Keeps the content on a single line */
        margin: 0 auto;
        /* Gives that scrolling effect as the typing happens */
        letter-spacing: .15em;
        animation:
            typing 3.5s steps(10, end) forwards,
            blink-caret .75s step-end infinite;

    }

    @media (min-width: 1170px) {

        /* The typing effect */
        @keyframes typing {
            from {
                width: 0
            }

            to {
                width: 28%
            }
        }
    }

    @media (max-width: 1169.99px) {

        /* The typing effect */
        @keyframes typing {
            from {
                width: 0
            }

            to {
                width: 37%
            }
        }
    }

    @media (max-width: 969.99px) {

        /* The typing effect */
        @keyframes typing {
            from {
                width: 0
            }

            to {
                width: 45%
            }
        }
    }

    @media (max-width: 769.99px) {

        /* The typing effect */
        @keyframes typing {
            from {
                width: 0
            }

            to {
                width: 70%
            }
        }
    }

    @media (max-width: 469.99px) {
        .container h1 {
            letter-spacing: 80%;
            font-size: 150%;
        }

        /* The typing effect */
        @keyframes typing {
            from {
                width: 0
            }

            to {
                width: 85%
            }
        }
    }

    /* The typewriter cursor effect */
    @keyframes blink-caret {
        from {
            border-color: transparent
        }

        to {
            border-color: transparent;
            opacity: 0;
        }

        50% {
            border-color: white;
        }
    }
</style>