import sys
import pandas as pd
import requests

if len(sys.argv) < 2:
    print("No file provided.")
    sys.exit(1)

excel_file = sys.argv[1]
df = pd.read_excel(excel_file)

url = "http://localhost/project/admin/insert_student.php"  # Change if needed

success, fail = 0, 0
for _, row in df.iterrows():
    data = {
        'name': row.get('STUDENT NAME', ''),
        'register_number': row.get('REG NO', ''),
        'email': row.get('EMAIL ID', ''),
        'password': row.get('PASSWORD', ''),
    }
    print(data)  # Add this line to see what is being sent
    if all(data.values()):
        response = requests.post(url, data=data)
        if response.status_code == 200 and response.text.strip() == "success":
            success += 1
        else:
            fail += 1
    else:
        fail += 1

print(f"Upload complete. Success: {success}, Failed: {fail}")