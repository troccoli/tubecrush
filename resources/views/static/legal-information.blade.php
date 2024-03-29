<x-app-layout>
    <x-slot name="header">
        <x-tubecrush-banner/>
    </x-slot>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 text-gray-700 leading-relaxed">
        <h2 class="mb-6 text-2xl sm:text-3xl">Legal Information</h2>

        <h3 class="mb-3 text-xl sm:text-2xl">Legal Statement</h3>
        <p class="mb-2">
            The aim of our site is in no way to infringe on people's personal right to privacy instead we celebrate the
            attractiveness of any subjects in photos that we receive and publish online. It is not illegal to take
            photos of people on the London underground network or in a public place where you wouldn't expect privacy
            however if you are featured in one of our posts and would prefer to have your photo removed then please see
            the section on photo removal below.
        </p>
        <p class="mb-2">
            For more information on the laws regarding taking photos of people click
            <a href="https://commons.wikimedia.org/wiki/Commons:Country_specific_consent_requirements"
               class="underline hover:text-red-600">here</a>.
        </p>

        <h3 class="mb-3 text-xl sm:text-2xl">Photo Removal</h3>
        <p class="mb-2">
            If you are featured in any of the photos on our site and you object to its presence, then you are free to
            ask for it to be removed, completing the
            <a href="{{ route('home') }}" class="underline hover:text-red-600">Photo Removal Request</a> form on our
            website. Once we have received the request we will email a reply within 24 hours
            to request either a link to, or an attachment of a recent photograph that demonstrates that you are the
            person featured on the website. The reason we do this is to protect your identity and verify that you are
            the person/s in the image also there are many spam bots that trawl websites and complete web forms such as
            this with spamming data and we have found this verification method to be the most robust.
        </p>

        <h3 class="mb-3 text-xl sm:text-2xl">Communication with people featured in photos and photo submitters</h3>
        <p class="mb-2">
            Please note that in the event where an individual communicates with another person who was either the
            subject of a photo featured on TubeCrush.net or was the submitter of a photo on TubeCrush.net is done so at
            the risk of that individual. In this regard, TubeCrush.net shall in no circumstances be liable for direct or
            indirect damage to the individual, person featured in the photo, or submitter of the photo where their
            respective behaviour contributed to the occurrence of the damage he/she claims to have suffered.
        </p>

        <h3 class="mb-3 text-xl sm:text-2xl">Cookies</h3>
        <p class="mb-2">
            <span class="font-bold">Tubecrush</span> does use some non-essential cookies. We do not do this to track
            individual users or to identify them, but to gain useful knowledge about how the site is used so that we can
            keep improving it for our users. Without the knowledge we gain from the systems that use these cookies we
            would not be able to provide the service we do.
        </p>
        <p class="mb-2">
            This site uses different types of cookie.
        </p>
        <p class="mb-2">
            We use Google Analytics, a popular web analytics service provided by Google, Inc. Google Analytics uses
            cookies to help us to analyse how users use the site. It counts the number of visitors and tells us things
            about their behaviour overall – such as the typical length of stay on the site or the average number of
            pages a user views.
        </p>
        <p class="mb-2">
            The information generated by the cookie about your use of our website (including your IP address) will be
            transmitted to and stored by Google on servers in the United States. Google will use this information for
            the purpose of evaluating your use of our website, compiling reports on website activity and providing other
            services relating to website activity and internet usage.
        </p>
        <p class="mb-2">
            Google may also transfer this information to third parties where required to do so by law, or where such
            third parties process the information on Google's behalf. Google undertakes not to associate your IP address
            with any other data held by Google.
        </p>
        <p class="mb-2">
            If you have Adobe Flash installed on your computer (most computers do) and you use Out-Law.com's audio or
            video players, Google Analytics will try to store some additional data on your computer. This data is known
            as a Local Shared Object or <span class="font-bold">Flash cookie</span>. This helps us to analyse the
            popularity of our media files. We can count the total number of times each file is played, how many people
            watch videos right to the end and how many people give up half way through. Adobe's website offers tools to
            <a href="http://www.macromedia.com/support/documentation/en/flashplayer/help/settings_manager07.html"
               class="underline hover:text-red-600">control Flash cookies on your computer</a>.
        </p>
        <p class="mb-2">
            Some third party cookies are set by services that appear on our pages. They are set by the operators of that
            service and are not in our control. They are set by Twitter, Facebook and Sharethis and relate to the
            ability of users to share content on this site, as indicated by their icons:
        </p>
        <p class="mb-2">
            If you want to delete any cookies that are already on your computer, please refer to the instructions for
            your file management software to locate the file or directory that stores cookies. You can access them
            through some types of browser. Search in your cookie folders for 'Tubecrush' to find our cookie and the
            Google Analytics cookie if you wish to delete them.
        </p>
        <p class="mb-2">
            More information about cookies, including how to block them or delete them, can be found at
            <a href="http://www.aboutcookies.org/" class="underline hover:text-red-600">AboutCookies.org</a>.
        </p>

        <h3 class="mb-3 text-xl sm:text-2xl">Our Trademarks</h3>
        <p class="mb-2">
            Tubecrush “RTM”</p>
        <p class="mb-2">
            Tube Crush “RTM”</p>
        <p class="mb-2">
            We have registered our brand with the intellectual property office. Our brand should not be used without our
            permission – to contact a member of our team to discuss this in more detail please email
            <a href="mailto:{{ config('mail.from.address') }}"
               class="text-blue-600 hover:underline">{{ config('mail.from.address') }}</a>.
        </p>
    </div>
</x-app-layout>
