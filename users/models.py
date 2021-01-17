from django.db import models
from django.utils import timezone
from django.contrib.auth.models import User

class UserProfile(models.Model):
	user = models.OneToOneField(User,on_delete=models.CASCADE)
	is_administrator = models.BooleanField(default=False)
	is_operator = models.BooleanField(default=False)
	is_student = models.BooleanField(default=False)

	def __str__(self):
		return self.user.last_name + ' ' + self.user.first_name[:1]

class StudentProfile(models.Model):
	user = models.OneToOneField(User,on_delete=models.CASCADE)
	k_f = models.CharField(max_length=8)
	pdf = models.FileField(upload_to='students/pdfs/',null=True, blank=True)
	pdf_date_uploaded = models.DateField(default=timezone.now, null=True, blank=True)

	def __str__(self):
		return self.user.last_name + ' ' + self.user.first_name[:1] + '. (' + self.k_f + ')' 