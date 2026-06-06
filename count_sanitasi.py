import pandas as pd
import glob

files = glob.glob(r'D:\Skripsi\*.xlsx')
total_sanitasi = 0
details = []

print("Scanning files...")
for f in files:
    if '~$' in f: continue
    try:
        df = pd.read_excel(f, sheet_name=None)
        file_count = 0
        for sheet, data in df.items():
            mask = data.apply(lambda row: row.astype(str).str.contains('sanitasi|wc|mck', case=False).any(), axis=1)
            count = mask.sum()
            if count > 0:
                file_count += count
                details.append(f"  - {f.split(chr(92))[-1]} (Sheet: {sheet}): {count} data")
        total_sanitasi += file_count
    except Exception as e:
        pass

print(f"\nTotal Keseluruhan Sanitasi/WC: {total_sanitasi} data")
print("Rincian:")
print("\n".join(details))
