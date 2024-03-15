<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>History of the year 1900</title>

        <!-- Fonts -->
        

        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>      
        <div class="bg-black py-6 text-center text-white">
            <h1 class="text-6xl">The Year 1900</h1>    
        </div>
    

        <?php foreach($run->historyPoems as $history): ?>
            <div class="bg-fixed bg-cover w-100 min-h-60 py-28 relative" style="background-image:url('{{ asset($history->id.'.png') }}')">
                <div class="absolute relative w-full h-full z-10">
                    <h2 class="text-2xl sm:text-4xl py-4 text-center text-white">{{$history->name}}</h2>

                    <div class="max-w-prose mx-auto px-5 sm:px-0">
                        <div class="text-white">{!! nl2br($history->description) !!}</div>

                        <div class="text-gray-300 italic mt-3 ml-3">
                            <div>{!! nl2br($history->poem) !!}</div>

                            <audio controls class="pt-3">
                              <source src="{{ asset($history->id.'.mp3') }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    </div>
                </div>
                <div class="absolute w-full h-full bg-black opacity-60 z-0 top-0 left-0"></div>
            </div>            
        <?php endforeach; ?>

        <div class="bg-black py-6 text-center text-white">
            <h3 class="text-2xl pt-10">About This Content</h3>

            <p class="px-10 mt-2 pb-10">                
                <span class="font-bold">All the above content was generated using OpenAI.</span>
                    
                Want to learn how I built this page? Read the deatailed article 
                <a href="https://articles.erickoyanagi.com" class="underline">here</a>.

            </p>

            <p>
                Found an issue? Want to get in touch? <a href="https://www.linkedin.com/in/erickoyanagi/" class="underline">Connect on LinkedIn</a>!
            </p>
        </div>
    </body>
</html>