from django.urls import path
from django.contrib.auth import views as auth_views
from users import views

app_name = 'users'

urlpatterns = [
	path('registration/',views.registration, name='registration'),
	path('register/student/',views.register_student, name='register_student'),
	path('profile/',views.profile, name='profile'),
]