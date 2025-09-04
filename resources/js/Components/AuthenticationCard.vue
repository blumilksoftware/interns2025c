<template>
  <div class="min-h-screen flex flex-col sm:justify-center items-center justify-center pt-6 sm:pt-0">
    <div class="flex justify-center items-center flex-col p-10 sm:shadow-xl/20 sm:rounded-[50px] bg-white w-full max-w-lg">
      <div>
        <slot name="logo" />
      </div>

      <div class="w-full mt-6 overflow-visible sm:rounded-lg">
        <slot />
      </div>
    </div>
  </div>
</template>
