from django.contrib import admin
from users.models import School, UserProfile, StudentProfile, StudentFileID, StudentFileEFKA, StudentFileIBAN, StudentFileDOY

admin.site.register(School)
admin.site.register(UserProfile)
admin.site.register(StudentProfile)
admin.site.register(StudentFileID)
admin.site.register(StudentFileEFKA)
admin.site.register(StudentFileIBAN)
admin.site.register(StudentFileDOY)