from django.contrib import admin
from users.models import UserProfile, StudentProfile, StudentFile1

admin.site.register(UserProfile)
admin.site.register(StudentProfile)
admin.site.register(StudentFile1)