from django.contrib import admin
from users.models import UserProfile, StudentProfile, StudentFileID, StudentFileEFKA, StudentFileIBAN, StudentFileDOY

admin.site.register(UserProfile)
admin.site.register(StudentProfile)
admin.site.register(StudentFileID)
admin.site.register(StudentFileEFKA)
admin.site.register(StudentFileIBAN)
admin.site.register(StudentFileDOY)