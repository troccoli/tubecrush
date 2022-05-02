@env('local')
<div class="w-full text-center">
    <p class="p-2 bg-gray-300 sm:hidden">DEFAULT</p>
    <p class="p-2 bg-red-300 hidden sm:block md:hidden">SMALL (sm:)</p>
    <p class="p-2 bg-yellow-300 hidden md:block lg:hidden">MEDIUM (md:)</p>
    <p class="p-2 bg-green-300 hidden lg:block xl:hidden">LARGE (lg:)</p>
    <p class="p-2 bg-blue-300 hidden xl:block 2xl:hidden">EXTRA LARGE (xl:)</p>
    <p class="p-2 bg-pink-300 hidden 2xl:block">MASSIVE (2xl:)</p>
</div>
@endenv
