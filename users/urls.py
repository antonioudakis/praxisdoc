from django.urls import path
from django.contrib.auth import views as auth_views
from users import views

app_name = 'users'

urlpatterns = [
	path('registration/',views.registration, name='registration'),
	path('register/student/',views.register_student, name='register_student'),
	path('profile/',views.profile, name='profile'),
	path('student/file1/delete/<int:pk>/',views.delete_file1, name='delete_file1'),
	path('student/file1/upload/',views.upload_file1, name='upload_file1'),
]