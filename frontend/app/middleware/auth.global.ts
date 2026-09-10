// app/middleware/auth.global.ts
export default defineNuxtRouteMiddleware((to) => {
  const token = useCookie('access_token')

  // Daftar rute publik yang bisa diakses tanpa login
  const publicRoutes = ['/login']
  const isPublicRoute = publicRoutes.includes(to.path)

  // 1. Jika belum login & mencoba buka halaman terproteksi -> Lempar ke /login
  if (!token.value && !isPublicRoute) {
    return navigateTo('/login')
  }

  // 2. Jika sudah login & mencoba buka halaman /login -> Lempar ke Dashboard
  if (token.value && to.path === '/login') {
    return navigateTo('/')
  }
})