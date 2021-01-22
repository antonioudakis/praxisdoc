from django.urls import path
from django.contrib.auth import views as auth_views
from users import views

app_name = 'users'

urlpatterns = [
	path('registration/',views.registration, name='registration'),
	path('register/student/',views.register_student, name='register_student'),
	path('profile/',views.profile, name='profile'),
	path('student/fileID/download/<int:pk>/',views.download_fileID, name='download_fileID'),
	path('student/fileID/delete/<int:pk>/',views.delete_fileID, name='delete_fileID'),
	path('student/fileID/upload/',views.upload_fileID, name='upload_fileID'),
	path('student/fileEFKA/download/<int:pk>/',views.download_fileEFKA, name='download_fileEFKA'),
	path('student/fileEFKA/delete/<int:pk>/',views.delete_fileEFKA, name='delete_fileEFKA'),
	path('student/fileEFKA/upload/',views.upload_fileEFKA, name='upload_fileEFKA'),
	path('student/fileIBAN/download/<int:pk>/',views.download_fileIBAN, name='download_fileIBAN'),
	path('student/fileIBAN/delete/<int:pk>/',views.delete_fileIBAN, name='delete_fileIBAN'),
	path('student/fileIBAN/upload/',views.upload_fileIBAN, name='upload_fileIBAN'),
	path('student/fileDOY/download/<int:pk>/',views.download_fileDOY, name='download_fileDOY'),
	path('student/fileDOY/delete/<int:pk>/',views.delete_fileDOY, name='delete_fileDOY'),
	path('student/fileDOY/upload/',views.upload_fileDOY, name='upload_fileDOY'),
]