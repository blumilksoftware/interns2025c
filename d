[1mdiff --git a/environment/dev/app/nginx.conf b/environment/dev/app/nginx.conf[m
[1mindex 3661266..80092dd 100644[m
[1m--- a/environment/dev/app/nginx.conf[m
[1m+++ b/environment/dev/app/nginx.conf[m
[36m@@ -21,8 +21,6 @@[m [mhttp {[m
     access_log /var/log/nginx/access.log main;[m
     sendfile on;[m
     keepalive_timeout 65;[m
[31m-    [m
[31m-    # Timeout settings[m
     client_body_timeout 60s;[m
     client_header_timeout 60s;[m
     send_timeout 60s;[m
[36m@@ -47,8 +45,6 @@[m [mhttp {[m
             fastcgi_pass unix:/run/php-fpm.sock;[m
             fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;[m
             include fastcgi_params;[m
[31m-            [m
[31m-            # FastCGI timeout settings[m
             fastcgi_connect_timeout 60s;[m
             fastcgi_send_timeout 60s;[m
             fastcgi_read_timeout 60s;[m
[1mdiff --git a/resources/js/Components/Header.vue b/resources/js/Components/Header.vue[m
[1mindex 3f90b3e..263614e 100644[m
[1m--- a/resources/js/Components/Header.vue[m
[1m+++ b/resources/js/Components/Header.vue[m
[36m@@ -14,59 +14,59 @@[m [mconst mobileMenuOpen = ref(false)[m
 </script>[m
 [m
 <template>[m
[31m-  <header class="">[m
[31m-    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">[m
[31m-      <div class="flex lg:flex-1">[m
[31m-        <a href="#" class="-m-1.5 p-1.5">[m
[31m-          <span class="sr-only">Interns2025c</span>[m
[31m-          <img class="h-8 w-auto" src="/Images/cat-dog.png" alt="">[m
[31m-        </a>[m
[31m-      </div>[m
[31m-      <div class="flex lg:hidden">[m
[31m-        <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = true">[m
[31m-          <span class="sr-only">Open main menu</span>[m
[31m-          <Bars3Icon class="size-6" aria-hidden="true" />[m
[31m-        </button>[m
[31m-      </div>[m
[31m-      <PopoverGroup class="hidden lg:flex lg:gap-x-12">[m
[31m-        <a href="#" class="text-sm/6 font-semibold text-gray-900">Adopt Me!</a>[m
[31m-        <a href="#" class="text-sm/6 font-semibold text-gray-900">About Adoption</a>[m
[31m-        <a href="#" class="text-sm/6 font-semibold text-gray-900">Contact Us</a>[m
[31m-      </PopoverGroup>[m
[31m-      <div class="hidden lg:flex lg:flex-1 lg:justify-end">[m
[31m-        <a href="#" class="text-sm/6 font-semibold text-gray-900">Log in <span aria-hidden="true">&rarr;</span></a>[m
[31m-      </div>[m
[31m-    </nav>[m
[31m-    <Dialog class="lg:hidden" :open="mobileMenuOpen" @close="mobileMenuOpen = false">[m
[31m-      <div class="fixed inset-0 z-50">[m
[31m-        <DialogPanel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-4 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">[m
[31m-          <div class="flex items-center justify-between sticky top-0 bg-white pb-4">[m
[31m-            <a href="#" class="-m-1.5 p-1.5">[m
[31m-              <span class="sr-only">Interns2025c</span>[m
[31m-              <img class="h-8 w-auto" src="/Images/cat-dog.png" alt="">[m
[31m-            </a>[m
[31m-            <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700 z-10" @click="mobileMenuOpen = false">[m
[31m-              <span class="sr-only">Close menu</span>[m
[31m-              <XMarkIcon class="size-6" aria-hidden="true" />[m
[31m-            </button>[m
[31m-          </div>[m
[31m-          <div class="mt-6 flow-root">[m
[31m-            <div class="-my-6 divide-y divide-gray-500/10">[m
[31m-              <div class="space-y-2 py-6">[m
[31m-                <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Adopt Me!</a>[m
[31m-                <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">About Adoption</a>[m
[31m-                <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Contact Us</a>[m
[31m-              </div>[m
[31m-              <div class="py-6">[m
[31m-                <a href="#" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Log in</a>[m
[32m+[m[32m    <header>[m
[32m+[m[32m      <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">[m
[32m+[m[32m        <div class="flex lg:flex-1">[m
[32m+[m[32m          <a href="#" class="-m-1.5 p-1.5">[m
[32m+[m[32m            <span class="sr-only">Interns2025c</span>[m
[32m+[m[32m            <img class="h-8 w-auto" src="/Images/cat-dog.png" alt="App logo"/>[m
[32m+[m[32m          </a>[m
[32m+[m[32m        </div>[m
[32m+[m[32m        <div class="flex lg:hidden">[m
[32m+[m[32m          <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = true">[m
[32m+[m[32m            <span class="sr-only">Open main menu</span>[m
[32m+[m[32m            <Bars3Icon class="size-6" aria-hidden="true" />[m
[32m+[m[32m          </button>[m
[32m+[m[32m        </div>[m
[32m+[m[32m        <PopoverGroup class="hidden lg:flex lg:gap-x-12">[m
[32m+[m[32m          <a href="#" class="text-sm/6 font-semibold text-gray-900">Adopt Me!</a>[m
[32m+[m[32m          <a href="#" class="text-sm/6 font-semibold text-gray-900">About Adoption</a>[m
[32m+[m[32m          <a href="#" class="text-sm/6 font-semibold text-gray-900">Contact Us</a>[m
[32m+[m[32m        </PopoverGroup>[m
[32m+[m[32m        <div class="hidden lg:flex lg:flex-1 lg:justify-end">[m
[32m+[m[32m          <a href="#" class="text-sm/6 font-semibold text-gray-900">Log in <span aria-hidden="true">&rarr;</span></a>[m
[32m+[m[32m        </div>[m
[32m+[m[32m      </nav>[m
[32m+[m[32m      <Dialog class="lg:hidden" @close="mobileMenuOpen = false" :open="mobileMenuOpen">[m
[32m+[m[32m        <div class="fixed inset-0 z-50">[m
[32m+[m[32m          <DialogPanel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-4 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">[m
[32m+[m[32m            <div class="flex items-center justify-between sticky top-0 bg-white pb-4">[m
[32m+[m[32m              <a href="#" class="-m-1.5 p-1.5">[m
[32m+[m[32m                <span class="sr-only">Interns2025c</span>[m
[32m+[m[32m                <img class="h-8 w-auto" src="/Images/cat-dog.png" alt="App logo" />[m
[32m+[m[32m              </a>[m
[32m+[m[32m              <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700 z-10" @click="mobileMenuOpen = false">[m
[32m+[m[32m                <span class="sr-only">Close menu</span>[m
[32m+[m[32m                <XMarkIcon class="size-6" aria-hidden="true" />[m
[32m+[m[32m              </button>[m
[32m+[m[32m            </div>[m
[32m+[m[32m            <div class="mt-6 flow-root">[m
[32m+[m[32m              <div class="-my-6 divide-y divide-gray-500/10">[m
[32m+[m[32m                <div class="space-y-2 py-6">[m
[32m+[m[32m                  <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Adopt Me!</a>[m
[32m+[m[32m                  <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">About Adoption</a>[m
[32m+[m[32m                  <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Contact Us</a>[m
[32m+[m[32m                </div>[m
[32m+[m[32m                <div class="py-6">[m
[32m+[m[32m                  <a href="#" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Log in</a>[m
[32m+[m[32m                </div>[m
               </div>[m
             </div>[m
[31m-          </div>[m
[31m-        </DialogPanel>[m
[31m-      </div>[m
[31m-    </Dialog>[m
[31m-  </header>[m
[31m-</template>[m
[32m+[m[32m          </DialogPanel>[m
[32m+[m[32m        </div>[m
[32m+[m[32m      </Dialog>[m
[32m+[m[32m    </header>[m
[32m+[m[32m  </template>[m
   [m
 <style scoped>[m
 </style>[m
[1mdiff --git a/resources/js/Pages/LandingPage/ImageSection.vue b/resources/js/Pages/LandingPage/ImageSection.vue[m
[1mindex 6978a7d..7d590a5 100644[m
[1m--- a/resources/js/Pages/LandingPage/ImageSection.vue[m
[1m+++ b/resources/js/Pages/LandingPage/ImageSection.vue[m
[36m@@ -66,7 +66,7 @@[m [mconst animals = [[m
       </div>[m
       <div class="mx-auto mt-16 grid max-w-2xl auto-rows-fr grid-cols-1 gap-8 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">[m
         <article v-for="post in animals" :key="post.id" class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 px-8 pt-80 pb-8 sm:pt-48 lg:pt-80">[m
[31m-          <img :src="post.imageUrl" alt="" class="absolute inset-0 -z-10 size-full object-cover">[m
[32m+[m[32m          <img :src="post.imageUrl" alt="Pet image" class="absolute inset-0 -z-10 size-full object-cover">[m
           <div class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40" />[m
           <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-gray-900/10" />[m
           <h3 class="mt-3 text-lg/6 font-semibold text-white">[m
